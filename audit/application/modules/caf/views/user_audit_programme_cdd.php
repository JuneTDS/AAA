
<script src="<?= base_url() ?>node_modules/bootbox/bootbox.min.js"></script>	

<script src="<?= base_url() ?>node_modules/jquery-validation/dist/jquery.validate.js"></script>
<link href="<?= base_url() ?>application/modules/caf/css/adjustment_popup.css" rel="stylesheet" type="text/css">

<style type="text/css">

.table-borderless > tbody > tr > td,
.table-borderless > tbody > tr > th,
.table-borderless > tfoot > tr > td,
.table-borderless > tfoot > tr > th,
.table-borderless > thead > tr > td,
.table-borderless > thead > tr > th 
{
    border: none;
    /*border: 1px solid black;*/
}

.custom-header
{
    background-color: #446687;
    color: white;
    text-align: center;
    border:1px solid black !important;
}

.black-bg
{
    background-color: black;
    border: 1px solid gray !important;
}

.bordered-cell
{
    border:1px solid black !important;
}

.content_main_line
{
    /*font-style: italic;*/
    font-weight: bold; 
}

.side-border
{
    border-left: 1px solid black !important;
    border-right: 1px solid black !important;
}

.divider-line
{
    border-bottom: 1px solid black !important;
    text-align: center;
}

</style>


<section role="main" class="content_section" style="margin-left:0;">
	<section class="panel" style="margin-top: 0px;">			
        <form id="user_audit_programme_cdd_form">

            <input type="hidden" class="caf_id" name="caf_id" value="<?=$caf_id ?>">
            <input type="hidden" class="id" name="id" value="<?=(isset($cdd_detail['id'])?$cdd_detail['id']:"")?>">

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="audit_objectives_table">
                <tr>
                    <td colspan="3">
                        <h4 style="color:black;padding:0;margin:0;"><b>AUDIT PROGRAMME - <?= (isset($programme_master_detail->title)?strtoupper($programme_master_detail->title):'')?></b></h4>
                    </td>
                </tr>


            </table>
            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="section_1_tbl">
                <tr>
                    <th class="content_main_line" colspan="3">SECTION 1</th>
        
                    <th class="custom-header">
                        Yes
                    </th>
                    <th class="custom-header">
                        No
                    </th>

                </tr>

                <?php
                    if(isset($cdd_detail['s1_1']))
                    {
                        $yes_checked = $cdd_detail['s1_1']==1?"checked":"";
                        $no_checked  = $cdd_detail['s1_1']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>

                <tr class="check_answer check_s1">
                    <td width="85%" colspan="3">The client is unable to provide all the required information in the relevant forms.</td>
        
                    <td width="7.5%" class="side-border divider-line">
                        <input type="radio" name="s1_1" <?=$yes_checked?> value="1">
                    </td>
                    <td width="7.5%" class="side-border divider-line">
                        <input type="radio" name="s1_1" <?=$no_checked?> value="0">
                    </td>

                </tr>

                <?php
                    if(isset($cdd_detail['s1_2']))
                    {
                        $yes_checked = $cdd_detail['s1_2']==1?"checked":"";
                        $no_checked  = $cdd_detail['s1_2']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer check_s1">
                    <td width="85%" colspan="3">The required information obtained cannot be verified to independent and reliable documents.</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s1_2" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s1_2" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s1_3']))
                    {
                        $yes_checked = $cdd_detail['s1_3']==1?"checked":"";
                        $no_checked  = $cdd_detail['s1_3']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr>
                    <td width="85%" colspan="3">The client, beneficial owner of the client, person acting on behalf of the client, or connected party of the client matches the details in the following lists: </td>
        
                    <td width="7.5%" class="side-border">
                    </td>
                    <td width="7.5%" class="side-border">       
                    </td>

                </tr>
                <tr>

                    <td width="5%" >(a) </td>
                    <td width="80%" colspan="2">The “Lists of Designated Individuals and Entities” on the MAS website; </td>
        
                    <td class="side-border">
                    </td>
                    <td class="side-border">
                    </td>

                </tr>
                <tr>

                    <td width="5%" >(b) </td>
                    <td width="80%" colspan="2">The “Terrorist Alert List” on the ISCA website; or</td>
        
                    <td class="side-border">
                    </td>
                    <td class="side-border">
                    </td>

                </tr>
                <tr>

                    <td width="5%" >(c) </td>
                    <td width="80%" colspan="2">Any other similar lists and information required of professional firms for screening purposes stipulated by relevant authorities in Singapore including the Accounting and Corporate Regulatory Authority;</td>
        
                    <td class="side-border">
                    </td>
                    <td class="side-border">
                    </td>

                </tr>
                <tr class="check_answer check_s1">
                    <td width="85%" colspan="3">and the exceptions cannot be disposed of satisfactorily.</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s1_3"<?=$yes_checked?>  value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s1_3" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s1_4']))
                    {
                        $yes_checked = $cdd_detail['s1_4']==1?"checked":"";
                        $no_checked  = $cdd_detail['s1_4']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer check_s1">
                    <td width="85%" colspan="3">There is suspicion of money laundering and/or terrorist financing.</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s1_4" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s1_4" <?=$no_checked?> value="0">
                    </td>

                </tr>
                
            </table>

            <!--  END OF SECTION 1 -->

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="section_2_tbl">
                <tr>
                    <th class="content_main_line" colspan="3">SECTION 2</th>
        
                    <th class="custom-header">
                        Yes
                    </th>
                    <th class="custom-header">
                        No
                    </th>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_1']))
                    {
                        $yes_checked = $cdd_detail['s2_1']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_1']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>

                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the client, any of the beneficial owner of the client or person acting on behalf of the client a Politically Exposed Person (PEP), family member of a PEP or close associate of a PEP?</td>
        
                    <td width="7.5%" class="side-border divider-line">
                        <input type="radio" name="s2_1" <?=$yes_checked?> value="1">
                    </td>
                    <td width="7.5%" class="side-border divider-line">
                        <input type="radio" name="s2_1" <?=$no_checked?> value="0">
                    </td>

                </tr>

                <?php
                    if(isset($cdd_detail['s2_2']))
                    {
                        $yes_checked = $cdd_detail['s2_2']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_2']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>

                <tr class="check_answer">
                    <td width="85%" colspan="3">The professional firm has performed further screening of details of client, beneficial owner of the client, person acting on behalf of the client, or connected party of the client against other information sources, for example, Google, the sanctions lists published by the Office of Foreign Assets Control of the US Department of the Treasury, and/or other third party screening database?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_2" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_2" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_3']))
                    {
                        $yes_checked = $cdd_detail['s2_3']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_3']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Are there adverse news or information arising?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_3" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_3" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_4']))
                    {
                        $yes_checked = $cdd_detail['s2_4']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_4']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the client in a high-risk industry?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_4" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_4" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_5']))
                    {
                        $yes_checked = $cdd_detail['s2_5']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_5']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Does the client have nominee shareholder(s) in the ownership chain where there is no legitimate rationale?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_5" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_5" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_6']))
                    {
                        $yes_checked = $cdd_detail['s2_6']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_6']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Where applicable, do the nominee shareholders represent majority ownership?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_6" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">

                        <input type="radio" name="s2_6" <?=$no_checked?> value="0">
                    </td>
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_7']))
                    {
                        $yes_checked = $cdd_detail['s2_7']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_7']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the client a shell company?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_7" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_7" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_8']))
                    {
                        $yes_checked = $cdd_detail['s2_8']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_8']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Does the client have unusual or complex shareholding structure (e.g. involving 3 layers or more of ownership structure, different jurisdictions, trusts), given the nature of its business?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_8" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_8" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_9']))
                    {
                        $yes_checked = $cdd_detail['s2_9']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_9']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the client a charitable or non-profit organisation that is NOT registered in Singapore?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_9" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_9" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_10']))
                    {
                        $yes_checked = $cdd_detail['s2_10']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_10']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the client’s business cash-intensive?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_10" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_10" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_11']))
                    {
                        $yes_checked = $cdd_detail['s2_11']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_11']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Does the client frequently make unaccounted cash transactions to similar recipients?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_11" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_11" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_12']))
                    {
                        $yes_checked = $cdd_detail['s2_12']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_12']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Are the client’s company accounts NOT updated?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_12" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_12" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_13']))
                    {
                        $yes_checked = $cdd_detail['s2_13']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_13']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Does the client’s shareholders and/or directors frequently change, and the changes are unaccounted for?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_13" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_13" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_14']))
                    {
                        $yes_checked = $cdd_detail['s2_14']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_14']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the client, beneficial owner of the client or person acting on behalf of the client from or based in a country or jurisdiction in relation to which the FATF has called for countermeasures? </td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_14" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_14" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_15']))
                    {
                        $yes_checked = $cdd_detail['s2_15']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_15']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">The following would be applicable: nationality, country of incorporation / registration, residential address, registered address, address of principal place of business.</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_15" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_15" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_16']))
                    {
                        $yes_checked = $cdd_detail['s2_16']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_16']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the client, beneficial owner of the client or person acting on behalf of the client from or based in a country or jurisdiction known to have inadequate AML/CFT measures?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_16" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_16" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_17']))
                    {
                        $yes_checked = $cdd_detail['s2_17']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_17']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">The following would be applicable: nationality, country of incorporation / registration, residential address, registered address, address of principal place of business. </td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_17" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_17" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_18']))
                    {
                        $yes_checked = $cdd_detail['s2_18']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_18']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Does the client, beneficial owner or person acting on behalf of the client have dealings in high risk jurisdictions?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_18" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_18" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_19']))
                    {
                        $yes_checked = $cdd_detail['s2_19']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_19']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is the business relationship with the client established through online, postal or telephone, where non face-to-face approach is used?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_19" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_19" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_20']))
                    {
                        $yes_checked = $cdd_detail['s2_20']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_20']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Has the client given any instruction to perform a transaction (which may include cash) anonymously?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_20" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_20" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_21']))
                    {
                        $yes_checked = $cdd_detail['s2_21']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_21']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Has the client transferred any funds without the provision of underlying services or transactions?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_21" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_21" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_22']))
                    {
                        $yes_checked = $cdd_detail['s2_22']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_22']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Are there unusual patterns of transactions that have no apparent economic purpose or cash payments that are large in amount, in which disbursement would have been normally made by other modes of payment (such as cheque, bank drafts etc.)?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_22" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_22" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_23']))
                    {
                        $yes_checked = $cdd_detail['s2_23']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_23']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Are there unaccounted payments received from unknown or un-associated third parties for services and/or transactions provided by the client?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_23" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_23" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_24']))
                    {
                        $yes_checked = $cdd_detail['s2_24']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_24']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is there instruction from the client to incorporate shell companies with nominee shareholder(s) and/or director(s)?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_24" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_24" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <tr>
                    <td width="85%" colspan="3">Does the client set-up or purchase companies or business entities that have no obvious commercial purpose such as: </td>
        
                    <td width="7.5%" class="side-border">
                    </td>
                    <td width="7.5%" class="side-border">
                    </td>

                </tr>

                    <td width="5%" > &bull;</td>
                    <td width="80%" colspan="2">
                    	Multi-layer, multi-country and complex group structures.
                    </td>
        
                    <td class="side-border">
                    </td>
                    <td class="side-border">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_25']))
                    {
                        $yes_checked = $cdd_detail['s2_25']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_25']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">

                    <td width="5%" > &bull;</td>
                    <td width="80%" colspan="2">Setting up entities in Singapore where there is no obvious commercial purpose, or any other personal or economic connection to the client.</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_25" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_25" <?=$no_checked?> value="0">
                    </td>

                </tr>
                <?php
                    if(isset($cdd_detail['s2_26']))
                    {
                        $yes_checked = $cdd_detail['s2_26']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_26']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">and the exceptions cannot be disposed of satisfactorily.</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_26" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_26" <?=$no_checked?> value="0">
                    </td>

                <tr>
                <?php
                    if(isset($cdd_detail['s2_27']))
                    {
                        $yes_checked = $cdd_detail['s2_27']==1?"checked":"";
                        $no_checked  = $cdd_detail['s2_27']==0?"checked":"";
                    }
                    else
                    {
                        $yes_checked = "";
                        $no_checked  = "";
                    }

                ?>
                <tr class="check_answer">
                    <td width="85%" colspan="3">Is there any divergence in the type, volume or frequency of services and/or transactions expected in the course of the business relationship with the client?</td>
        
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_27" <?=$yes_checked?> value="1">
                    </td>
                    <td class="side-border divider-line">
                        <input type="radio" name="s2_27" <?=$no_checked?> value="0">
                    </td>

                <tr>
                
            </table>

            <!-- END OF SECTION 2 -->

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="section_3_tbl">
            	<tr>
	            	<th class="content_main_line" width="20%">SECTION 3</th>
	            	<th class="bordered-cell text-center" width="20%">
	                    Risk
	                </th>
	        
	                <th class="custom-header" width="20%">
	                    CDD
	                </th>
	                <th class="custom-header" width="40%">
	                    Reasons
	                </th>
	            </tr>
	            <tr>
	                <td width="20%">Client risk rating</td>
	            	<td width="20%" class="bordered-cell text-center s3_risk">
	      
	                </td>
	        
	                <td width="20%" class="bordered-cell text-center s3_cdd">

	                </td>
	                <td width="40%" class="bordered-cell">
	                    <textarea class="form-control" name="s3_reason" rows="1"><?=(isset($cdd_detail['s3_reason'])?$cdd_detail['s3_reason']:"")?></textarea>
	                </td>
	            </tr>
            	
            </table>
            <!-- END OF SECTION 3 -->

            <table class="table table-borderless" style="border-collapse: collapse; margin: 2%; width: 95%; color:black" id="section_4_tbl">
            	<tr>
	            	<th width="40%" class="content_main_line">SECTION 4</th>
	            	<th width="20%"class="bordered-cell">
	                </th>
	        
	                <th width="40%" class="custom-header">
	                    Remark
	                </th>
	            </tr>

	            <tr>
	                <td width="40%">Business relationship</td>
	            	<td width="20%" class="bordered-cell text-center s4_relationship">
	                </td>
	        
	                <td width="40%" class="bordered-cell">
	                    <textarea class="form-control" name="s4_reason" rows="1"><?=(isset($cdd_detail['s4_reason'])?$cdd_detail['s4_reason']:"")?></textarea>
	                </td>
	            </tr>
            	
            </table>
            <!-- END OF SECTION 4 -->
            
        </form>

	</section>
</section>

<div class="loading" id='loadingMessage' style='display:none'>Loading&#8230;</div>

<?php include('application/modules/caf/template/adjustment_popup.html'); ?>




<script type="text/javascript">
	var base_url = '<?php echo base_url(); ?>'; 

    var save_programme_cdd_input_url = "<?php echo site_url('caf/save_programme_cdd_input'); ?>";
    var export_programme_cdd_pdf_url = "<?php echo (site_url('caf/export_programme_cdd_pdf')).'/'.$caf_id.'/'.$assignment_id; ?>";
</script>


<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_cdd.js" charset="utf-8"></script>
<script src="<?= base_url()?>application/modules/caf/js/user_audit_programme_toolbar.js" charset="utf-8"></script>
<!-- Adjustment function  -->
<script src="<?= base_url()?>application/modules/caf/js/adjustment_popup.js" charset="utf-8"></script>