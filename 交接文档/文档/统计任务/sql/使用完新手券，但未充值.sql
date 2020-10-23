-- 使用完新手券，但未充值
SELECT u.mobile,user1.`user_id`,uso.id FROM (SELECT `user_id` FROM `user_order` WHERE `use_ticket` =2 AND `build_time` >= 1574006400 AND `liqui_time`>= 1574006400) AS user1
LEFT JOIN `user_saving_order` AS uso ON user1.user_id=uso.user_id 
LEFT JOIN `user` AS u ON u.id=user1.user_id
WHERE uso.id IS NULL
GROUP BY  user1.user_id
