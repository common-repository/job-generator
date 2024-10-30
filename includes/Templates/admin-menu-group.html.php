<div class="wrap">
    <h1 id="group-name"><?php echo HelloworkJobs::$params->get('group')->name(); ?></h1>
    <div id="poststuff">
        <div id="post-body" class="columns-2">
            <div id="postbox-container" class="postbox-container job-list">
                <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                        <div class="postbox " id="test1">
                            <h2 class="hndle ui-sortable-handle"><span>求人一覧</span></h2>
                            <div class="jobs">
                                <?php if(HelloworkJobs::$params->has('jobs') && count(HelloworkJobs::$params->get('jobs'))>0): ?>
                                <table>
                                    <tr>
                                        <th>タイトル</th>
                                        <th>ショートコード(通常)</th>
                                        <th>ショートコード(アコーディオン型)</th>
                                        <th>ステータス</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td>
                                            すべての求人
                                        </td>
                                        <td>
                                            <input type="text" value="[hwjbgrp id='<?php echo HelloworkJobs::$params->get('group')->ID;?>']" onClick="this.select();" readonly>
                                        </td>
                                        <td>
                                            <input type="text" value="[hwjbgrp id='<?php echo HelloworkJobs::$params->get('group')->ID;?>' type='mini']" onClick="this.select();" readonly>
                                        </td>
                                        <td></td>
                                    </tr>
                                        <?php foreach(HelloworkJobs::$params->get('jobs') as $job):?>
                                            <tr>
                                                <td>
                                                    <?php echo $job->offered_job; ?>
                                                </td>
                                                <td>
                                                    <input type="text" value="[hwjb id='<?php echo $job->getPostId();?>']" onClick="this.select();" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" value="[hwjb id='<?php echo $job->getPostId();?>' type='mini']" onClick="this.select();" readonly>
                                                </td>
                                                <td>
                                                    <?php if($job->trashed()):?>
                                                        募集終了
                                                    <?php else:?>
                                                        募集中
                                                    <?php endif; ?>

                                                </td>
                                                <td>
                                                    <form action="" method="POST" class="">
                                                        <?php wp_nonce_field( 'del-job_'.$job->getPostId() );?>
                                                        <input type="hidden" name="action" value="del-job">
                                                        <input type="hidden" name="group" value="<?php echo HelloworkJobs::$params->get('group')->ID;?>">
                                                        <input type="hidden" name="job" value="<?php echo $job->getPostId();?>">
                                                        <button class="button button-default delete-job">削除</button>
                                                    </form>
                                                    
                                                </td>
                                            </tr>
                                        <?php endforeach;?>

                                </table>


                                <div class="description">
                                    <span class="help-block">
                                        *ショートコードを投稿や固定ページに貼り付けると、その投稿またはページを表示した場合に、求人情報が表示されます。<br><br>
                                        *[すべての求人]のショートコードを投稿や固定ページに貼り付けると求人一覧に表示されているすべての求人が表示されます。<br>
                                    </span>
                                </div>
                                
                                <?php else:?>
                                    求人がありません。
                                <?php endif; ?>
                                <div class="other">
                                    <form action="" method="POST" class="update">
                                        <?php wp_nonce_field("upd-grp_" . HelloworkJobs::param('group')->ID);?>
                                        <input type="hidden" name="action" value="upd-grp">
                                        <input type="hidden" name="group" value="<?php echo HelloworkJobs::$params->get('group')->ID;?>">
                                        <button class="button button-primary">求人情報を最新の状態に更新</button>
                                    </form>
                                    <form action="" method="POST" class="">
                                        <?php wp_nonce_field("del-grp_" . HelloworkJobs::param('group')->ID);?>
                                        <input type="hidden" name="action" value="del-grp">
                                        <input type="hidden" name="group" value="<?php echo HelloworkJobs::$params->get('group')->ID;?>">
                                        <button class="button button-default delete-group">すべて削除</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                        <div class="postbox " id="test1">
                            <h2 class="hndle ui-sortable-handle"><span>設定</span></h2>
                            <div class="jobs">
                                <form action="" method="POST" class="job-form job-group-conf">
                                    <ul>
                                        <li>
                                            <input type="checkbox" name="auto_update" value="1" 
                                            <?php if (HelloworkJobs::$params->get('group')->isAutoUpdate()):?> 
                                                checked
                                            <?php endif;?>
                                            >求人情報を自動更新する
                                        </li>
                                        <li class="hw-link">
                                            <input type="checkbox" name="show_hw_link" value="1" 
                                            <?php if (HelloworkJobs::$params->get('group')->showHwLink()):?> 
                                                checked
                                            <?php endif;?>
                                            >
                                            <p class="desc">
                                                ハローワークインターネットサービスへのリンクを付加する
                                                <span class="help-block">
                                                    *ページがスマートフォンで表示されている場合は、スマートフォン対応のHelloworkNextまたはHelloworkPartへのリンクが付加されます。
                                                </span>
                                            </p>
                                            
                                        </li>
                                    </ul>
                                    <?php wp_nonce_field("upd-grp-config_" . HelloworkJobs::param('group')->ID);?>
                                    <input type="hidden" name="group" value="<?php echo HelloworkJobs::$params->get('group')->ID;?>">
                                    <input type="hidden" name="action" value="upd-grp-config">
                                    <button class="button button-primary">変更</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                    <!-- <form action="" method="POST" class="job-form"> -->
                        <div class="postbox " id="test1">
                            <h2 class="hndle ui-sortable-handle"><span>この求人情報の取得条件</span></h2>
                            <div class="inside conditions">
                                <?php if(HelloworkJobs::$session->hasError()):?>
                                    <div id="message" class="notice notice-error is-dismissible">
                                        <p><?php echo HelloworkJobs::$session->getError(); ?></p>
                                    <button type="button" class="notice-dismiss">
                                        <span class="screen-reader-text">この通知を非表示にする</span>
                                    </button>
                                </div>
                                <?php endif;?>

                                <div >
                                    <label>電話番号</label><br>
                                    <!-- <input type="text" name="tell" placeholder="ここに電話番号を入力"> -->
                                    <span><?php echo HelloworkJobs::$params->get('group')->phone(); ?></span>
                                    <br>
                                    <label>タイトル</label><br>
                                    <span><?php echo HelloworkJobs::$params->get('group')->name(); ?></span>
                                </div>
                                <div>
                                    <label>求人種類</label><br>
                                    <span><?php echo HelloworkJobs::$params->get('group')->empFmt(); ?></span>
                                </div>
                                <div>
                                    <label>職種</label><br>
                                    <!-- <select id="hwjt" name="hwjt[]" multiple placeholder="指定しない"> -->
                                        <?php if(empty(HelloworkJobs::$params->get('group')->jobTypes())):?>
                                            <span>指定なし</span>
                                        <?php else:?>
                                            <?php foreach(HelloworkJobs::$params->get('group')->jobTypes() as $key=>$val): ?>
                                                <span><?php echo $val; ?></span>
                                            <?php endforeach; ?>
                                        <?php endif;?>
                                    <!-- </select> -->
                                </div>
                                <div>
                                    <label>勤務地</label><br>
                                    <?php if(empty(HelloworkJobs::$params->get('group')->locations())):?>
                                        <span>指定なし</span>
                                    <?php else:?>
                                        <?php foreach(HelloworkJobs::$params->get('group')->locations() as $key=>$val): ?>
                                            <span><?php echo $val;?></span><br>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        </div>
                    <!-- </form> -->
                </div>
            </div>
            
        </div>
    </div>
</div>