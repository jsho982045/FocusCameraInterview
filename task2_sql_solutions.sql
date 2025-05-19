-- Query 1: Total Tasks Per User

SELECT
    u.id AS user_id,               -- select users id
    u.name AS user_name,           -- select users name
    COUNT(t.id) AS task_count      -- count # of task ids for each user
FROM
    users u                        -- start with users table 
LEFT JOIN                          -- left join to include all users even if they have no tasks    
    tasks t ON u.id = t.user_id

GROUP BY                           -- Group results by user id and name
    u.id, u.name
ORDER BY
    u.id;                          -- order by user id



-- Query 2: Task Completion Rate
SELECT
    u.id AS user_id,               -- select users id
    u.name AS user_name,           -- select users name
    COUNT(t.id) AS total_tasks,    -- count # of task ids for each user
    SUM(CASE WHEN t.is_completed = TRUE THEN 1 ELSE 0 END) AS completed_tasks  -- count # of tasks where is_completed is true sum will increment 1 if true 0 if false
FROM
    users u                        -- start with users table
LEFT JOIN                          -- left join to include all users even if they have no tasks
    tasks t ON u.id = t.user_id
GROUP BY                           -- Group results by user id and name
    u.id, u.name
ORDER BY
    u.id;                          -- order by user id


-- Query 3: Task Created in the Last 7 Days
SELECT
    DATE(t.created_at) AS creation_date,                      -- Extract the date pat from created_at field
    COUNT(t.id) AS task_count                                 -- count # of task ids for tgat date
FROM
    tasks t
WHERE
    DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) -- Gets the date 6 days before current date
    AND DATE(t.created_at) <= CURDATE()                       -- Ensures we only include up tp the current date
GROUP BY
    creation_date                                             -- Group results by creation date
ORDER BY
    creation_date DESC;                                      -- order by creation date showing most recent first



-- Query 4: Most Productive User
SELECT
    u.id AS user_id,                   -- select users id
    u.name AS user_name,               -- select users name
    COUNT(t.id) AS completed_tasks     -- count # of completed tasks
FROM
    users u                            -- start with users table
INNER JOIN
    tasks t ON u.id = t.user_id
WHERE
    t.is_completed = TRUE               -- Filter to only include completed tasks
GROUP BY
    u.id, u.name                       -- Group results by user id and name
ORDER BY
    completed_tasks DESC               -- order by completed tasks in descending order
LIMIT 1;                               -- take only the top result = most productive user


