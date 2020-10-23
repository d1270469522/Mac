-- 9月、10月，整体用户充值1次、2次、3次以上用户人数
SELECT u.mobile,u.nickname,uso.`user_id` , COUNT( uso.`user_id` ) AS number
FROM `user_saving_order` AS uso
LEFT JOIN user AS u ON u.id =  uso.`user_id`
WHERE uso.`add_time` >=1567267200
AND uso.`add_time` <1572537600
AND uso.`status` =1
AND uso.`app_id` =5
GROUP BY uso.`user_id`
ORDER BY number ；

-- 现金交易1、2、3、4 次以上用户人数；及拉活
SELECT u.mobile,u.nickname,uo.`user_id` , COUNT( uo.`user_id` ) AS number
FROM `user_order` AS uo
LEFT JOIN user AS u ON u.id =  uo.`user_id`
WHERE uo.`liqui_time` >=1567267200
AND uo.`liqui_time` <1572537600
AND uo.`status` =2
AND uo.`app_id` =5
GROUP BY uo.`user_id`
ORDER BY number 