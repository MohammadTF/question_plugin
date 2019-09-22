
<?php
/*
	 * needed for security reasons
	 */
    wp_nonce_field( 'gh_question_save', 'rudr_metabox_nonce' );
    $data = get_post_meta($post->ID, 'gohar_e_hikmat_questions',true);
    $link = get_post_meta($post->ID, 'gohar_e_hikmat_pdf',true);
    $release = get_post_meta($post->ID, 'gh_release',true);
    $answer_display = get_post_meta($post->ID, 'gh_answer_display',true);
?>
<div>
    <label for="gh_pdf">Upload PDF:</label>
    <input type="file" name="gh_pdf" id="gh_pdf">
    <?php if($link): ?>
    <a href="<?php echo $link;?>" target="_blank">View</a>
    <?php endif; ?>
    <br>
    <label for="gh_pdf_roman">Upload PDF in Roman:</label>
    <input type="file" name="gh_pdf_roman" id="gh_pdf_roman">
    <?php if($linkroman): ?>
    <a href="<?php echo $linkroman;?>" target="_blank">View</a>
    <?php endif; ?>
    <div>
            <label for="release">Release Date:</label>
            <input type="date" name="gh_release" value="<?php echo $release;?>" id="release">
            
        </div>

        <div>
            <label for="answer_display">Answer Display Date:</label>
            <input type="date" name="gh_answer_display" value="<?php echo $answer_display;?>" id="answer_display">
            
        </div>

</div>

<div id="gh_question_wrapper">
<?php if(empty($data)): ?>
    <div class="gh_question_inner">
        <div>
            <label for="question_1">Question:</label>
            <input type="text" name="gh_question[0][title]" value="" id="question_1">
            
        </div>
       
        <div>
            <label for="correct_answer_1">Correct Answer:</label>
            <input type="text" name="gh_question[0][correct_answer]" value="" id="correct_answer_1">
            
        </div>
        <div>
            <div>
                <label for="option_1_1">Option 1</label>
                <input type="text" class="options" id="option_1_1" name="gh_question[0][option][]" value="" >
                
            </div>
            <div>
                <label for="option_1_2">Option 2</label>
                <input type="text" class="options" id="option_1_2" name="gh_question[0][option][]" value="" >
            </div>
            <div>
                <label for="option_1_3">Option 3</label>
                <input type="text" class="options" id="option_1_3" name="gh_question[0][option][]" value="" >
            </div>
            <!-- <div>
                <label for="option_1_4">Option 4</label>
                <input type="text" class="options" id="option_1_4" name="gh_question[0][option][]" value="" >
            </div> -->
        </div>
        <div>
            <a href="#" class="gh_clone-btn gh_add-btn">Add Question</a>
            <a href="#" class="gh_clone-btn gh_rem-btn">Remove Question</a>            
        </div>

    </div>

<?php else: ?>
<?php
foreach($data as $d=>$v)
{
    $index = $d + 1;
?>
 

<div class="gh_question_inner">
        <div>
            <label for="question_<?php echo $index;?>">Question:</label>
            <input type="text" name="gh_question[<?php echo $d;?>][title]" value="<?php echo $v['title']?>" id="question_<?php echo $index;?>">
            
        </div>
       
        <div>
            <label for="correct_answer_<?php echo $index;?>">Correct Answer:</label>
            <input type="text" name="gh_question[<?php echo $d;?>][correct_answer]" value="<?php echo $v['correct_answer']?>" id="correct_answer_<?php echo $index;?>">
            
        </div>
        <div>
            <div>
                <label for="option_<?php echo $index;?>_1">Option 1</label>
                <input type="text" class="options" id="option_<?php echo $index;?>_1" name="gh_question[<?php echo $d;?>][option][0]" value="<?php echo $v['option'][0]?>" >
                
            </div>
            <div>
                <label for="option_<?php echo $index;?>_2">Option 2</label>
                <input type="text" class="options" id="option_<?php echo $index;?>_2" name="gh_question[<?php echo $d;?>][option][1]" value="<?php echo $v['option'][1];?>" >
            </div>
            <div>
                <label for="option_<?php echo $index;?>_3">Option 3</label>
                <input type="text" class="options" id="option_<?php echo $index;?>_3" name="gh_question[<?php echo $d;?>][option][2]" value="<?php echo $v['option'][2]?>" >
            </div>
            <!-- <div>
                <label for="option_1_4">Option 4</label>
                <input type="text" class="options" id="option_1_4" name="gh_question[0][option][]" value="" >
            </div> -->
        </div>
        <div>
            <a href="#" class="gh_clone-btn gh_add-btn">Add Question</a>
            <a href="#" class="gh_clone-btn gh_rem-btn">Remove Question</a>            
        </div>

    </div>
<?php
}
 ?>
<?php endif; ?>
</div>
<script>
jQuery().ready(function($){
    $('.gh_add-btn').on('click',function(e){
        e.preventDefault();
        var length  = $('.gh_question_inner').length;
        var index   = +length + 1;
        
        var clonned = $('.gh_question_inner').first().clone(true);
        clonned.find('label[for="question_1"]').attr("for","question_"+index);
        clonned.find('label[for="question_'+index+'"]').next().attr("id","question_"+index)
                                            .attr("name","gh_question["+index+"][title]")
                                            .attr("val","");

        clonned.find('label[for="correct_answer_1"]').attr("for","correct_answer_"+index);
        clonned.find('label[for="correct_answer_'+index+'"]').next().attr("id","correct_answer_"+index)
                                            .attr("name","gh_question["+index+"][correct_answer]")
                                            .attr("val","");
        
        clonned.find('label[for="option_1_1"]').attr("for","option_"+index+"_1");
        clonned.find('label[for="option_'+index+'_1"]').next().attr("id","option_"+index+"_1")
                                            .attr("name","gh_question["+index+"][option][]")
                                            .attr("val","");

        clonned.find('label[for="option_1_2"]').attr("for","option_"+index+"_2");
        clonned.find('label[for="option_'+index+'_2"]').next().attr("id","option_"+index+"_2")
                                            .attr("name","gh_question["+index+"][option][]")
                                            .attr("val","");

        clonned.find('label[for="option_1_3"]').attr("for","option_"+index+"_3");
        clonned.find('label[for="option_'+index+'_3"]').next().attr("id","option_"+index+"_3")
                                            .attr("name","gh_question["+index+"][option][]")
                                            .attr("val","");

        // clonned.find('label[for="option_1_4"]').attr("for","option_"+index+"_4");
        // clonned.find('label[for="option_'+index+'_4"]').next().attr("id","option_"+index+"_4")
        //                                     .attr("name","gh_question["+index+"][option][]")
        //                                     .attr("val","");
        debugger;
        $('#gh_question_wrapper').append(clonned);

    });
    $('.gh_rem-btn').on('click',function(e){
        e.preventDefault();
        if($('.gh_question_inner').length > 1)
        {
            $(this).parent().parent().remove();
        }
        
    });
});
</script>