<style>
.modal {
    background: rgba( 255, 255, 255, .8 ) 
        url("<?php echo HelloworkJobs::$url . "/assets/images/loading.gif";?>")
        50% 50% 
        no-repeat;
}
</style>
<div class="wrap">
    <h1>ハローワーク求人の編集</h1>
    <div id="successNotice" class="notice notice-success ">
        <p class="msg"></p>
    </div>
    <div id="poststuff">
        <div id="post-body" class="columns-2">
            <div id="postbox-container" class="postbox-container">
                <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                    <form action="" method="POST" class="job-form">
                        <div class="postbox " id="test1">
                            <h2 class="hndle ui-sortable-handle"><span>求人情報の生成</span></h2>
                            <div class="inside">
                                <?php if(HelloworkJobs::$session->hasError()):?>
                                    <div id="message" class="notice notice-error is-dismissible">
                                        <p><?php echo HelloworkJobs::$session->getError(); ?></p>
                                        <button type="button" class="notice-dismiss">
                                            <span class="screen-reader-text">この通知を非表示にする</span>
                                        </button>
                                    </div>
                                <?php endif;?>
                                <?php if(HelloworkJobs::$session->hasSuccess()):?>
                                    <div id="message" class="notice notice-success is-dismissible">
                                        <p><?php echo HelloworkJobs::$session->getSuccess(); ?></p>
                                        <button type="button" class="notice-dismiss">
                                            <span class="screen-reader-text">この通知を非表示にする</span>
                                        </button>
                                    </div>
                                <?php endif;?>


                                <div >
                                    <label>電話番号</label><br>
                                    <input type="text" name="tell" placeholder="ここに電話番号を入力">
                                    <span class="help-block">
                                        *ハローワークに登録している電話番号を<b>ハイフン付き</b>で正確に入力してください。<br>
                                    </span>
                                    <br>
                                    <label>タイトル</label><br>
                                    <input type="text" name="title" placeholder="ここにタイトルを入力">
                                    <span class="help-block">
                                        *求人情報を識別するための名前を入力してください。<br>
                                        例: フルタイムの求人
                                    </span>
                                </div>
                                <div>
                                    <label>求人種類</label><br>
                                    <input type="radio" name="emp_fmt" value="next">フルタイム<br>
                                    <input type="radio" name="emp_fmt" value="part">パートタイム<br>
                                </div>
                                <div>
                                    <label>職種</label><br>
                                    <select id="hwjt" name="hwjtm[]" multiple placeholder="指定しない">
                                        <!-- <option>指定しない</option> -->
                                        <!-- <option>営業</option> -->
                                        <?php foreach(HelloworkJobs::$params->get('categories') as $key=>$val): ?>
                                            <option value="<?php echo $key; ?>">
                                                <?php echo $val;?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label>勤務地</label><br>
                                    <select id="locations" name="locations[]" multiple placeholder="指定しない">
                                        <?php foreach(HelloworkJobs::$params->get('todofuken') as $key=>$val): ?>
                                            <option value="<?php echo $key . "-all"; ?>">
                                                <?php echo $val;?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <?php wp_nonce_field("gen-jobs");?>
                                    <input type="hidden" name="action" value="gen-jobs">
                                    <input type="submit" name="send" class="button button-primary" value="生成">
                                </div>
                                <div class="description">
                                    <span class="help-block">
                                        生成ボタンをクリックすると、ハローワークの最新の求人情報を取得し、求人情報を生成します。
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div id="postbox-container-1" class="postbox-container job-list">
                <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                        <div class="postbox " id="test1">
                            <h2 class="hndle ui-sortable-handle"><span>求人情報一覧</span></h2>
                            <div class="jobs">

                                <?php if(HelloworkJobs::param('jobGroups')): ?>
                                <table>
                                    <tr>
                                        <th>タイトル</th>
                                        <th>ショートコード</th>
                                        <th>ショートコード(アコーディオン)</th>
                                        <th></th>
                                    </tr>
                                        <?php foreach(HelloworkJobs::$params->get('jobGroups') as $group):?>
                                            <tr>
                                                <td>
                                                    <a href="?page=hellowork-jobs&group=<?php echo $group->ID; ?>"><?php echo $group->post_title; ?></a>
                                                </td>
                                                <td>
                                                    <input type="text" value="[hwjbgrp id='<?php echo $group->ID;?>']" onClick="this.select();" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" value="[hwjbgrp id='<?php echo $group->ID;?>' type='mini']" onClick="this.select();" readonly>
                                                </td>
                                                <td>
                                                    <?php wp_nonce_field("hwjb_update_jobs_by_group_{$group->ID}");?>
                                                    <button title="この求人を最新の情報に更新する" data-group-id="<?php echo $group->ID;?>" class="update-job button button-small">更新</button>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                </table>
                                <?php else:?>
                                    なし
                                <?php endif; ?>

                            <div class="description">
                                <span class="help-block">
                                    ショートコードを投稿や固定ページに貼り付けると、その投稿またはページを表示した場合に、求人情報が表示されます。
                                </span>
                            </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal"></div>
