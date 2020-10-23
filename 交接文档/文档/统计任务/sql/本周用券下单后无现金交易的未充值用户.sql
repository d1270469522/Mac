SELECT u.mobile, u.`id`,sum(`saving_num`) AS money ,COUNT(uo.`id`) AS order_num FROM `user` AS u
LEFT JOIN `user_saving_order` AS uso ON uso.user_id = u.id AND uso.`status`=1
LEFT JOIN `user_order` AS uo ON  uo.user_id = u.id AND uo.use_ticket =0
WHERE u.`reg_time`>=1574611200 
GROUP BY  u.`id`
HAVING money IS NULL AND order_num <>0
LIMIT 10000