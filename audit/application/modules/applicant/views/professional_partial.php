<script src="<?= base_url() ?>application/js/fileinput.js" type="text/javascript"></script>

<div class="professional_detail panel">
    <div class="card-header panel-heading title_bg">
        <span class="mb-0">
            <?php 
                $displayNum = $count+1;

                echo '<a class="btn btn-link interview_title" data-toggle="collapse" data-target="#collapse_pro'.$displayNum.'" aria-expanded="true" aria-controls="collapseOne">Professional Membership '.$displayNum.'</a>'
            ?>
        </span>
        <div class="pull-right" style="padding: 1.5%;">
            <span class="glyphicon glyphicon-trash deleteIcon" onclick="cancel_professional(this, <?=isset($content['id'])?$content['id']:''?>)"></span>
        </div>
    </div>

    <input type="hidden" name="pro_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">

    <?php echo '<div id="collapse_pro'.$displayNum.'" class="pro_collapse show">' ?>
        <div class="card-body panel-body">
            <div style="padding: 5%;">
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 26%;float:left;margin-right: 20px;">
                            <label>Professional Body :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="pro_body[<?=$count?>]" name="pro_body[<?=$count?>]" style="width: 500%;" value="<?=isset($content['professional_body'])?$content['professional_body']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 26%;float:left;margin-right: 20px;">
                            <label>Membership no. :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="pro_no[<?=$count?>]" name="pro_no[<?=$count?>]" style="width: 500%;" value="<?=isset($content['membership_no'])?$content['membership_no']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 26%;float:left;margin-right: 20px;">
                            <label>Membership type :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="pro_type[<?=$count?>]" name="pro_type[<?=$count?>]" style="width: 500%;" value="<?=isset($content['membership_type'])?$content['membership_type']:''?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 26%;float:left;margin-right: 20px;">
                            <label>Membership awarded on :</label>
                        </div>
                        <div style="width: 30%;float: left;">
                            <div class="input-group date" data-provide="datepicker">
                                <input type="text" class="form-control" id="pro_awarded[<?=$count?>]" name="pro_awarded[<?=$count?>]" value="<?=isset($content['membership_awarded'])?$content['membership_awarded']:''?>">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="display:none">
                    <div style="width: 100%;">
                        <div style="width: 26%;float:left;margin-right: 20px;">
                            <label>Upload Certificate :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 100%;">
                                <div class="file-loading">
                                    <input type="file" id="pro_cert[<?=$count?>]" class="file" name="pro_cert[<?=$count?>]" data-min-file-count="0" accept="image/*" multiple>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS here -->
<style>
    .inline_block div {
        display: inline-block;
    }
</style>

<script>
    $('.pro_collapse').collapse();
</script>