-- 上周有交易本周无交易的流失用户（含手机号）导出来，100个以内就行，我做下用户回访
SELECT u.`nickname` AS 用户账号,u.mobile AS 用户手机号,from_unixtime(u.`reg_time`, '%Y-%m-%d %T') AS 注册时间,from_unixtime(MAX(uo.build_time), '%Y-%m-%d %T')  AS 建仓时间, from_unixtime(MAX(uo.liqui_time), '%Y-%m-%d %T')  AS 平仓时间,sum(uo.`trade_fee`) AS 手续费,sum(uo.`pro_loss`) AS 盈亏,us.`money` AS 总充值金额,dt.pay AS 最后提现,dt.income AS 最后充值, from_unixtime(MAX(dt.`add_time`), '%Y-%m-%d %T')  AS 最后提现充值时间,ua.total_balance AS 总余额,
                sum(CASE
                        WHEN  uo.`build_time`>=1575216000 OR uo.`liqui_time`>=1575216000 THEN 1
                               else 0
                          END
                        ) AS nuw_num,
                sum(CASE
                        WHEN  uo.`build_time`<1575216000 OR uo.`liqui_time`<1575216000 THEN 1
                               else 0
                          END
                        ) AS last_num
                FROM `user_order`  AS uo
                LEFT JOIN `user` AS u ON u.`id` = uo.`user_id`
                LEFT JOIN (SELECT `user_id`,sum(`saving_num`) AS money FROM `user_saving_order` WHERE `status`=1 GROUP BY `user_id`) AS us ON us.`user_id` = u.`id`
                LEFT JOIN (SELECT `user_id`,`add_time`,`pay`,`income`FROM `user_money_log` WHERE `type`=0 GROUP BY  `user_id` ORDER BY `add_time`) AS dt ON dt.`user_id` = u.`id`
                LEFT JOIN  `user_app` AS ua ON ua.`user_id` = u.`id`
                WHERE (uo.`build_time`>=1574611200 OR uo.`liqui_time`>=1574611200) AND uo.`use_ticket`=0
                GROUP BY uo.`user_id`
                HAVING last_num <>0 AND nuw_num =0
                LIMIT 1000