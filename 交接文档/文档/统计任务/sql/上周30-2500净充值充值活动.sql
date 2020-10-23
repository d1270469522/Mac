SELECT u.mobile AS 手机号, SUM( uso.`saving_num` ) AS money, uso.`user_id`
FROM `user_saving_order` AS uso
LEFT JOIN user AS u ON u.id = `user_id`
WHERE uso.`status` =1
AND uso.`add_time` >=1575216000
AND uso.`user_id`
IN (

SELECT `user_id`
FROM (

SELECT `user_id` , SUM( `saving_num` ) AS money
FROM `user_saving_order`
WHERE `status` =1
AND `add_time` <1575216000
GROUP BY `user_id`
HAVING money >=30
AND money <=2500
) AS um
)
GROUP BY uso.`user_id`
HAVING money >=3200
ORDER BY money DESC