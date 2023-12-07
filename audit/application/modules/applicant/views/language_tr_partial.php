<tr class="lang_tr">
    <td>
        <input type="hidden" name="lang_id[<?= $count ?>]" value="<?=isset($content['id'])?$content['id']:''?>">
        <input class="form-control" name="lang_name[<?= $count ?>]" value="<?=isset($content['name'])?$content['name']:''?>">
    </td>
    <td>
        <select class="1-10" name="lang_spoken[<?= $count ?>]" value="<?=isset($content['spoken'])?$content['spoken']:''?> "></select>
    </td>
    <td>
        <select class="1-10" name="lang_written[<?= $count ?>]" value="<?=isset($content['written'])?$content['written']:''?> "></select>
    </td>
    <td><span class="glyphicon glyphicon-trash" onclick="cancel_lang(this, <?=isset($content['id'])?$content['id']:''?>)" style="cursor: pointer;"></span></td>
</tr>

<script>
    var $select = $(".1-10");
    $select.prepend($('<option></option>').val(0).html(""));

    for (i=0;i<=10;i++){
        $select.prepend($('<option></option>').val(i+1).html(i));
    }

    $("select[name='lang_spoken[<?= $count ?>]']").val(<?=isset($content['spoken'])?$content['spoken']:''?>);
    $("select[name='lang_written[<?= $count ?>]']").val(<?=isset($content['written'])?$content['written']:''?>);
</script>