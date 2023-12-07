<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/fileinput.css" />
<script src="<?= base_url() ?>application/js/fileinput.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>application/css/plugin/toastr.min.css" />
<script src="<?= base_url() ?>application/js/toastr.min.js"></script>

<div class="education_detail panel">
    <div class="card-header panel-heading title_bg">
        <span class="mb-0">
            <?php 
                $displayNum = $count+1;

                echo '<a class="btn btn-link interview_title" data-toggle="collapse" data-target="#collapse_edu'.$displayNum.'" aria-expanded="true" aria-controls="collapseOne">Education '.$displayNum.'</a>'
            ?>
        </span>
        <div class="pull-right" style="padding: 1.5%;">
            <span class="glyphicon glyphicon-trash deleteIcon" onclick="cancel_education(this, <?=isset($content['id'])?$content['id']:'' ?>)"></span>
        </div>
    </div>

    <input type="hidden" class="edu_id" name="edu_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">

    <?php echo '<div id="collapse_edu'.$displayNum.'" class="edu_collapse show">' ?>
        <div class="card-body panel-body">
            <div style="padding: 5%;">
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Institute/ University :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="edu_Institute[<?=$count?>]" name="edu_uni_name[<?=$count?>]" style="width: 500%;" value="<?=isset($content['uni_name'])?$content['uni_name']:''?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Graduation Date :</label>
                        </div>
                        <div style="float:left;margin-bottom:5px;" class="inline_block">
                            <div>
                                <?php
                                    echo form_dropdown('edu_graduate_month['.$count.']', $months, isset($content['graduate_month'])?$content['graduate_month']:'', 'style="width:100%;"');
                                ?>
                            </div>
                            <div>
                                <?php 
                                    echo '<select name="edu_graduate_year['.$count.']" class="yearpicker" style="width: 100%"></select>';
                                ?>
                                
                                <script>
                                    $('.yearpicker').append('<option>Year</option>');

                                    for (i = new Date().getFullYear() + 5; i > 1957; i--)
                                    {
                                        $('.yearpicker').append($('<option />').val(i).html(i));
                                    }
                                    $("select[name='edu_graduate_year[<?= $count ?>]']").val(<?=isset($content['graduate_year'])?$content['graduate_year']:''?>);
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Qualification :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <?php
                                    echo form_dropdown('edu_qualification['.$count.']', $qualification, isset($content['qualification'])?$content['qualification']:'', 'class="edu_qualification-select"');
                                ?>
                                <script>
                                    $(".edu_qualification-select").chosen({no_results_text: "Oops, nothing found!"});
                                </script>
                            </div>

                            <div id="form_name"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Institute/University Location :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div>
                                <?php
                                    echo form_dropdown('edu_uni_country['.$count.']', $country, isset($content['uni_country'])?$content['uni_country']:'', 'class="edu_uni_country-select style="width:100%;"');
                                ?>
                                <script>
                                    $(".edu_uni_country-select").chosen({no_results_text: "Oops, nothing found!"});
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Field of Study :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <?php
                                    echo form_dropdown('edu_uni_fieldOfStudy['.$count.']', $fieldOfStudy, isset($content['uni_fieldOfStudy'])?$content['uni_fieldOfStudy']:'', 'class="edu_uni_fieldOfStudy-select style="width:100%;"');
                                ?>
                                <script>
                                    $(".edu_uni_fieldOfStudy-select").chosen({no_results_text: "Oops, nothing found!"});
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Major :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <input type="text" class="form-control" id="edu_major[<?=$count?>]" name="edu_major[<?=$count?>]" style="width: 500%;" value="<?=isset($content['major'])?$content['major']:''?>" />
                            </div>

                            <div id="form_name"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Grade :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 20%;">
                                <?php
                                    echo form_dropdown('edu_grade['.$count.']', $grade, isset($content['grade'])?$content['grade']:'', 'class="edu_grade-select" onchange="grade_type(this)"');
                                ?>
                                <script>
                                    $(".edu_grade-select").chosen({no_results_text: "Oops, nothing found!"});
                                </script>
                            </div>

                            <div id="form_name"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group cgpa" style="display: none;">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Score :</label>
                        </div>
                        <div style="float:left;margin-bottom:5px;" class="inline_block">
                            <div style="width: 20%">
                                <input type="number" name="edu_cgpa[<?=$count?>]" class="form-control" value="<?=isset($content['score'])?$content['score']:''?>" >
                            </div>
                            <div style="width: 15%; text-align: center;"><label> out of </label></div>
                            <div style="width: 20%">
                                <input type="number" name="edu_total_cgpa[<?=$count?>]" class="form-control" value="<?=isset($content['total_score'])?$content['total_score']:''?>" >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Additional Information :</label>

                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <textarea class="form-control" rows="5" id="edu_add_Info[<?=$count?>]" name="edu_add_Info[<?=$count?>]" style="width:100%" "form-control"><?php echo isset($content['additional_info'])?$content['additional_info']:''; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group" style="display:none">
                    <div style="width: 100%;">
                        <div style="width: 25%;float:left;margin-right: 20px;">
                            <label>Upload Certificate :</label>
                        </div>
                        <div style="width: 65%;float:left;margin-bottom:5px;">
                            <div class="input-group" style="width: 100%;">
                                <div class="file-loading">
                                    <?php 
                                        echo '<input type="file" id="edu_cert['.$count.']" class="file" name="edu_cert['.$count.']" data-min-file-count="0" accept="image/*" multiple>';
                                    ?>
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
    var grade = '<?php echo isset($content['grade'])? $content['grade']: "" ?>';

    if(grade == "CGPA/Percentage"){
        var cgpa_section = $("input[name='edu_cgpa[<?php echo $count; ?>]']").parent().parent().parent().parent();
        
        cgpa_section.show();
    }
    
    var input_name = "#" + '<?php echo "edu_cert[" . $count . "]" ?>';
    
    $('.edu_collapse').collapse();

    $(input_name).fileinput({
        theme: 'fa',
        uploadUrl: '/payroll/applicant/uploadFile_education', // you must set a valid URL here else you will get an error
        uploadAsync: false,
        browseClass: "btn btn_blue",
        fileType: "any",
        required: true,
        showCaption: false,
        showUpload: false,
        showRemove: false,
        //showClose: false,
        autoReplace: true,
        overwriteInitial: true,
        maxFileCount: 1,
        fileActionSettings: {
                        showRemove: true,
                        showUpload: false,
                        showZoom: true,
                        showDrag: false,
                    },
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
        initialPreviewShowDelete: false,
        initialPreviewAsData: true,
        initialPreviewDownloadUrl: base_url + 'uploads/applicant/education/{filename}',
        initialPreview: initialPreviewArray,
        initialPreviewConfig: initialPreviewConfigArray,
        allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "ico", "Icon"],
        //deleteUrl: "/dot/personprofile/deleteFile",
        /*maxFileSize: 20000048,
        maxImageWidth: 1000,
        maxImageHeight: 1500,
        resizePreference: 'height',
        resizeImage: true,*/
        purifyHtml: true // this by default purifies HTML data for preview
        /*uploadExtraData: { 
            officer_id: $('input[name="officer_id"]').val() 
        }*/
        /*width:auto;height:auto;max-width:100%;max-height:100%;*/

    }).on('filesorted', function(e, params) {
        console.log('File sorted params', params);
    }).on('filebatchuploadsuccess', function(event, data, previewId, index) {
        $("#loadingmessage").hide();
        window.location.href = base_url + "applicant";
        toastr.success('Information Updated', 'Updated');
        console.log(data);
    }).on('fileuploaderror', function(event, data, msg) {
        $("#loadingmessage").hide();
        window.location.href = base_url + "applicant";
        toastr.success('Information Updated', 'Updated');
        console.log("Error");
    });

    function grade_type(element){
        var value = $(element).val();
        var score_section = $(element).parent().parent().parent().parent().parent().find('.cgpa');

        if(value == "CGPA/Percentage"){
            score_section.show();
        }else{
            if(score_section.is(':visible')){
                score_section.hide();
            }
        }
    }
</script>