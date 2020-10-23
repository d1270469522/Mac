-- 使用完新手券的 2天未现金下单未充值的     push 【今日智投】尊敬的用户，您的账户收到一张120元现金券，有效期7天，请尽快登陆APP查看并使用。
SELECT  u.id,u.mobile, count(uso.id) AS number FROM `user` AS u 
LEFT JOIN `user_saving_order` AS uso ON u.id=uso.user_id AND  uso.status =1
WHERE u.id IN(SELECT `user_id` FROM (
SELECT uo.`user_id` , uo.status,
	sum(CASE 
		WHEN  uo.`liqui_time`>= 1574870400 AND uo.`liqui_time`< 1574956800 AND uo.`use_ticket`=2 THEN 1
                else 0
          END
        ) AS novice_ticket,
        sum(CASE 
		WHEN  uo.`use_ticket`= 0  THEN -1
                WHEN  uo.`use_ticket`= 1  THEN -1
                WHEN  uo.`use_ticket`= 2  THEN 2
          END
        ) AS integral
        FROM `user_order` AS uo WHERE  (uo.status =2 AND uo.`liqui_time`>= 1574870400) OR uo.`build_time`>= 1574870400 GROUP BY uo.`user_id` HAVING novice_ticket =1 AND integral =2) AS userId) 
GROUP BY u.id 
HAVING number =0

--  使用完新手券的 有现金下单 ,2天未充值的     push 【今日智投】尊敬的用户，您的账户收到一张280元现金券，有效期7天，请尽快登陆APP查看并使用。
SELECT  u.id,u.mobile, count(uso.id) AS number FROM `user` AS u 
LEFT JOIN `user_saving_order` AS uso ON u.id=uso.user_id AND  uso.status =1
WHERE u.id IN(SELECT `user_id` FROM (
SELECT uo.`user_id` , uo.status,
	sum(CASE 
		WHEN  uo.`liqui_time`>= 1574870400 AND uo.`liqui_time`< 1574956800 AND uo.`use_ticket`=2 THEN 1
                else 0
          END
        ) AS novice_ticket,
        sum(CASE 
		WHEN  uo.`use_ticket`= 0  THEN 1
                WHEN  uo.`use_ticket`= 1  THEN 1
                WHEN  uo.`use_ticket`= 2  THEN 0
          END
        ) AS integral
        FROM `user_order` AS uo WHERE   (uo.status =2 AND uo.`liqui_time`>= 1574870400) OR uo.`build_time`>= 1574870400 GROUP BY uo.`user_id` HAVING novice_ticket =1 AND integral <>0) AS userId) 
GROUP BY u.id 
HAVING number =0


