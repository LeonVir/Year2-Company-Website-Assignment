<?php
include 'db_connection.php';
// set this at last
$createHolidayTriggerSQL = "
    CREATE TRIGGER employee_holiday_insert
    AFTER INSERT ON employees
    FOR EACH ROW
    BEGIN
        INSERT INTO holiday_balance (
            employee_id, annual_leave, sick_leave, personal_leave
        ) VALUES (
            NEW.employee_id, 28, 10, 5
        );
    END;

";
$conn->multi_query($createHolidayTriggerSQL);

$createProcedureSQL = "
    CREATE PROCEDURE delete_employee_with_log(
        IN emp_id INT,
        IN deleted_by_id INT,
        IN deleted_by_name VARCHAR(100),
        IN reason TEXT
    )
    BEGIN
        DECLARE emp_name VARCHAR(100);
        DECLARE emp_salary VARCHAR(10);
        DECLARE emp_department VARCHAR(50);
        DECLARE emp_position VARCHAR(50);
        DECLARE emp_dob DATE;
        DECLARE emp_nin VARCHAR(50);

        SELECT 
            e.name, e.salary, d.department_name, p.position_name, e.dob, e.nin
        INTO 
            emp_name, emp_salary, emp_department, emp_position, emp_dob, emp_nin
        FROM 
            employees e
        JOIN 
            department d ON e.department_id = d.department_id
        JOIN 
            position p ON e.position_id = p.position_id
        WHERE 
            e.employee_id = emp_id;

        INSERT INTO delete_employee_logs (
            employee_id,
            employee_name,
            employee_salary,
            employee_department,
            employee_position,
            employee_dob,
            employee_nin,
            deleted_by_id,
            deleted_by_name,
            reason,
            deleted_time
        ) VALUES (
            emp_id, emp_name, emp_salary, emp_department, emp_position, emp_dob, emp_nin, deleted_by_id, deleted_by_name, reason, NOW()
        );

        DELETE FROM employees WHERE employee_id = emp_id;
    END;
";
$conn->multi_query($createProcedureSQL);

$createPasswordTriggerSQL = "
    CREATE TRIGGER employee_password
    AFTER INSERT ON employees
    FOR EACH ROW
    BEGIN
        INSERT INTO login (
            employee_id, password_hash
        ) VALUES (
            NEW.employee_id, '" . password_hash('0000', PASSWORD_BCRYPT) . "'
        );
    END;
";
$conn->multi_query($createPasswordTriggerSQL);
$conn->close();
