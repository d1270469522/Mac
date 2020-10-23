SELECT u.mobile AS 手机号,
from_unixtime( u.reg_time, '%Y-%m-%d %T') AS 注册时间,
ua.total_balance AS 可用总余额,
sum(uot.trade_fee) AS 手续费,
sum(uot.pro_loss) AS 盈亏量,
u.`id`,sum(usot.`saving_num`) AS money 
FROM `user` AS u
LEFT JOIN `user_app` AS ua ON ua.user_id = u.id
LEFT JOIN (SELECT `user_id`,sum(`pro_loss`) AS pro_loss,sum(`trade_fee`) AS trade_fee FROM `user_order`  WHERE `status` =2 GROUP BY `user_id`) AS uot ON uot.`user_id` = u.id
LEFT JOIN (SELECT `user_id`,sum(saving_num) AS saving_num FROM `user_saving_order` AS uso WHERE `status`=1 GROUP BY user_id)AS usot ON usot.`user_id` = u.id
WHERE u.`id` IN(
SELECT aaa.user_id FROM(SELECT uso.user_id,sum(uso.`saving_num`) AS money ,
sum(
	CASE 
		WHEN  uo.`liqui_time`>= 1572537600 AND uo.`liqui_time`< 1574611200 THEN 1
                WHEN  uo.`build_time`>= 1572537600 AND uo.`build_time`< 1574611200 THEN 1
          END
        ) AS old_num,
sum(
	CASE 
		WHEN  uo.`liqui_time`>= 1574611200 THEN 1
                WHEN  uo.`build_time`>= 1574611200 THEN 1
          END
        ) AS new_num
FROM `user_saving_order` AS uso  
LEFT JOIN `user_order` AS uo ON  uo.user_id = uso.user_id 
WHERE uso.`status`=1 AND (uo.`build_time`>=1572537600 OR uo.`liqui_time`>=1572537600)
GROUP BY uso.user_id
HAVING old_num IS NOT NULL AND new_num IS NULL) AS aaa

) GROUP BY u.id HAVING money>3000 LIMIT 1000;