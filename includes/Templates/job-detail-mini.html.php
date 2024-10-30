<?php $job = HelloworkJobs::param('job'); ?>
<div id="hwjb ">
        <div class="job-block">
            <table class="summary">
                <tr>
                    <th>求人番号</th>
                    <td><?php echo $job->hellowork_id ;?></td>
                </tr>
                <tr>
                    <th>職種</th>
                    <td><?php echo $job->offered_job ;?></td>
                </tr>
                <tr>
                     <th>内容</th>
                    <td><?php echo $job->job_detail ;?></td>                   
                </tr>
                <tr>
                    <th>月収</th>
                    <td>
                        <?php echo $job->salary() ;?>円〜<?php echo $job->salary(false) ;?>円
                    </td>
                </tr>
                <tr class="show-all">
                    <td colspan="2" >詳細を見る</td>
                </tr>
            </table>
            <table class="detail">
                <tr>
                    <th>内訳</th>
                    <td>
                        <div>
                            <h4>基本給:</h4>
                            <span><?php echo $job->baseMonthlyPay() ;?>円〜<?php echo $job->baseMonthlyPay(false) ;?>円</span>
                        </div>
                        <div>
                            <h4>定額手当:</h4>
                            <?php if($job->hasExtraMonthlyPay()): ?>
                                <span><?php echo $job->extraMonthlyPay() ;?>円〜<?php echo $job->extraMonthlyPay(false) ;?>円"</span>
                                <?php if($job->fixed_allowance): ?>
                                    <div><?php echo $job->fixed_allowance ;?></div>
                                <?php endif; ?> 
                            <?php else: ?> 
                                なし
                            <?php endif; ?> 

                        </div>
                        <div>
                            <h4>ボーナス:</h4>
                            <span><?php echo $job->bonus_memo ;?></span>
                        </div>
                        <div>
                            <h4>その他:</h4>
                            <span><?php echo $job->pay_other_memo ;?></span>
                        </div>
                        <div>
                            <h4>給与体系:</h4>
                            <span>
                                <span><?php echo $job->getPayUnit() ;?></span><br>
                                <?php if($job->pay_unit_memo): ?>
                                    <span><?php echo $job->pay_unit_memo ;?></span>
                                <?php endif; ?> 
                            </span>
                        </div>
                    </td>
                    
                </tr>
                <tr>
                    <th><i class="fa fa-id-card-o"></i>雇用形態</th>
                    <td class="">
                        <?php echo $job->getEmployeeType(); ?>
                    </td>
                </tr>
                <tr>
                    <th><i class="fa fa-graduation-cap"></i>応募資格</th>
                    <td class="">
                                <h4>学歴</h4>
                                <span>:<?php echo $job->ed_record_memo ;?></span><br>
                                <h4>経験</h4>
                                <span>:<?php echo $job->experience_memo ;?></span><br>
                                <h4>資格</h4>
                                <span>:<?php echo $job->quolification ;?></span><br>
                                <h4>年齢</h4>
                                <span>
                                    :<?php echo $job->getAgeRequirement();?>
                                    <?php if($job->age_reason): ?>
                                        <br><?php echo $job->age_reaso ;?>n
                                    <?php endif; ?> 
                                </span><br>
                                <h4>募集人数</h4>
                                <span>:<?php echo $job->vacancy ;?>人</span><br>
                    </td>
                </tr>
                <tr>
                    <th><i class="fa fa-car"></i>勤務地</th>
                    <td class="">
                        <div>
                            <?php foreach($job->locations as $loc):?>
                                <span><?php echo $loc->todofuken->name ;?><?php echo $loc->city->name; ?></span><br>
                            <?php endforeach;?>
                            <h4>交通費</h4>
                            <span>:
                                <?php echo  $job->getTransportAllowance();?><br>
                                <?php if($job->transport_allowance): ?>
                                    <?php echo $job->tr_amount ;?>円 / <?php echo $job->getTransportAllowanceUnit() ;?>
                                <?php endif; ?> 
                            </span>
                            <h4>転勤</h4>
                            <span>:
                                <?php if($job->relocation): ?>
                                    あり
                                <?php else: ?> 
                                    なし
                                <?php endif; ?> 
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th><i class="fa fa-bed"></i>休日</th>
                    <td class="">
                        <h4>年間休日</h4>
                        <span>:<?php echo $job->y_daysoff ;?>日</span><br>
                        <h4>休日曜日</h4>
                        <span>:<?php echo $job->dayoff_memo ;?></span><br>
                        <h4>週休2日</h4>
                        <span>:<?php echo $job->dayoff_memo2 ;?></span><br>
                    </td>
                </tr>
                <tr>
                    <th><i class="fa fa-clock-o"></i>勤務時間</th>
                    <td class="">
                        <h4>就業時間</h4>
                        <span>:<?php echo $job->work_hours ;?></span><br>
                        <h4>休憩時間</h4>
                        <span>:<?php echo $job->break_time ;?>分</span><br>
                        <h4>残業時間</h4>
                        <span>:<?php echo $job->overtime ;?>時間/月(平均)</span><br>
                    </td>
                </tr>
                <tr>
                    <th><i class="fa fa-sticky-note"></i>備考</th>
                    <td class="">
                        <?php echo  $job->memo?:"なし" ;?> 
                    </td>
                </tr>

                 <!-- todo@shira:なしじゃなくて、表示しないほうがいい /-->
                 <tr>
                   <th><i class="fa fa-plus-square"></i>労働環境&amp;福利厚生</th>
                   <td class="">
                    <h4>加入保険</h4>
                    <span>:<?php echo $job->ins_memo ;?></span><br>
                    <h4>社宅</h4>
                    <span>:<?php echo $job->getRoom() ;?></span><br>
                    <h4>託児所</h4>
                    <span>:<?php echo $job->getChildCareCenter() ;?></span><br>
                    <h4>社宅</h4>
                    <span>:<?php echo  $job->sleep_in?'あり':'なし' ;?></span><br>
                    <h4>車通勤</h4>
                    <span>:<?php echo $job->car_commute_memo ;?></span><br>
                    <h4>退職制度</h4>
                    <span>:<?php echo $job->getRetirement() ;?></span><br>
                    <h4>再雇用</h4>
                    <span>:<?php echo $job->getReemployment() ;?></span><br>
                    </td>
                </tr>
                <tr>
                     <th><i class="fa fa-check-square-o"></i>選考方法</th>
                     <td class="">
                        <h4>選考方法</h4>
                        <span>:<?php echo $job->screening ;?></span><br>
                        <h4>結果の通知</h4>
                        <span>:<?php echo $job->result ;?></span><br>
                        <h4>応募書類等</h4>
                        <span>:<?php echo $job->requirements ;?></span><br>
                        <h4>選考日時</h4>
                        <span>:<?php echo $job->screening_day ;?></span><br>
                        <h4>有効期限</h4>
                        <span>:<?php echo $job->valid_until ;?></span><br>
                    </td>
                </tr>
                <tr>
                    <th>備考</th>
                    <td><?php echo $job->memo2 ;?></td>
                </tr>
                <tr class="hide-detail">
                    <td colspan="2">
                        詳細を閉じる
                    </td>
                </tr>
            </table>
        </div>
</div>