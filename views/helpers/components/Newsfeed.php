<?php 
/*
function showNewsfeed($arr_updatesVOstatus, $arr_allComments) {
    
    $processedStatus = '';
    
    foreach($arr_updatesVOstatus as $updatesVOstatus)
    {
        if(!preg_match("/{$updatesVOstatus->getStatusId()}/", $processedStatus))
        {
        /*@var $updatesVOstatus UpdatesVOStatus *
        ?>
        <article class="updates-wrapper">
            <section class="updates">
                <table id="status_<?php echo $updatesVOstatus->getStatusId();?>"
                        border="0" cellpadding="0" cellspacing="0" style="width: 100%">
                    <tr>
                        <td style="width:100px; vertical-align:top">
                            <a href="index.php?rt=friends/view/id/<?= $updatesVOstatus->getProfileId(); ?>">
                                <img width="62" src="
                                    <?php 
                                    $profilePicLoc = IMAGES."profilepics/{$updatesVOstatus->getProfileId()}.jpg";
                                    if(file_exists($profilePicLoc))
                                            echo $profilePicLoc;
                                    else
                                            echo(IMAGES."profilepics/default.jpg"); 
                                    ?>" />
                            </a>
                        </td>  
                        <td>
                            <section class="comment-text">
                                <?php echo $updatesVOstatus->getDisplayname(); ?><br/>
                                <span class="quote"><?php echo $updatesVOstatus->getStatus(); ?></span>&nbsp;
                                <span class="date">
                                    <?php
                                    if(substr($updatesVOstatus->getDate(),0,10) == date('Y-m-d')) 
                                        echo 'at '. date('g:i a', strtotime($updatesVOstatus->getDate()));
                                    else 
                                        echo 'on '. date('d-M', strtotime($updatesVOstatus->getDate()));
                                    ?>
                                </span>&nbsp;
                            </section>
                            <section class="like-box">
                                <?php
                                if($updatesVOstatus->getNoOfLikes() > 0)
                                {
                                    echo '<a class="likeDetails" onclick="getLikeDetails(\'status\', '. $updatesVOstatus->getStatusId() .')">';

                                    if($updatesVOstatus->getNoOfLikes() == 1)
                                    {
                                        if($updatesVOstatus->getILiked() == 1)
                                            echo 'You like this';
                                        else
                                            echo '1 people like this';
                                    }
                                    else
                                    {
                                        if($updatesVOstatus->getILiked() == 1)
                                            echo 'You and '.($updatesVOstatus->getNoOfLikes() - 1).' other people like this';
                                        else
                                            echo $updatesVOstatus->getNoOfLikes().' people like this';
                                    }

                                    echo '</a>';

                                }
                                else
                                    echo '<a class="likeDetails">&nbsp;</a>';
                                ?>
                                <div style="float:right;">
                                    <?php
                                    if($updatesVOstatus->getILiked() == 1)
                                        echo '<a class="like" onclick="unlike(\'status\','.$updatesVOstatus->getStatusId().')">Unlike</a>';
                                    else
                                        echo '<a class="like" onclick="like(\'status\','.$updatesVOstatus->getStatusId().')">Like</a>';
                                    ?>
                                </div>
                            </section>
                        </td>
                    </tr>
                    <?php
                    // Checking if current status has comments
                        if(isset($arr_allComments[$updatesVOstatus->getStatusId()])) {
                            
                            $arr_updatesVOComment = $arr_allComments[$updatesVOstatus->getStatusId()];
                        
                            foreach($arr_updatesVOComment as $updatesVOComment)
                            {
                                /* @var $updatesVOComment UpdatesVOComment *
                                ?>
                                <tr class="comments">
                                    <td><?php echo $updatesVOComment->getProfileId(); ?></td>
                                    <td><?php echo $updatesVOComment->getDisplayname(); ?></td>
                                    <td><?php echo $updatesVOComment->getComment(); ?></td>
                                    <td>
                                        <?php
                                        if(substr($updatesVOComment->getDate(),0,10) == date('Y-m-d')) 
                                            echo 'at '. date('g:i a', strtotime($updatesVOComment->getDate()));
                                        else 
                                            echo 'on '. date('d-M', strtotime($updatesVOComment->getDate()));
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo $updatesVOComment->getCommentId(); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if($_SESSION['displayname'] == $updatesVOComment->getDisplayname())
                                            echo '1';
                                        else
                                            echo '0';
                                        ?>
                                    </td>
                                    <td><?php echo $updatesVOComment->getNoOfLikes() ; ?></td>
                                    <td><?php echo $updatesVOComment->getILiked() ; ?></td>
                                </tr>
                                <?php
                            }
                        }
                    ?>
                 </table>
            </section>
            <form onsubmit="return commentOnStatus(this, <?php echo $updatesVOstatus->getStatusId();?>)">
                    <input type="text" name="comment" maxlength="200" />
                    <input type="submit" value="Comment"/>
            </form>
        </article>
        <?php 
        if($processedStatus == '')
            $processedStatus = $updatesVOstatus->getStatusId();
        else
            $processedStatus .= ", {$updatesVOstatus->getStatusId()}";
        }
    }
}
*/
function showNewsfeed($arr_updatesVOstatus, $arr_allComments) {
    
    loadComponent('Status');
    loadComponent('Comment');
    
    // Display all status messages
    foreach($arr_updatesVOstatus as $updatesVOstatus)
    {
        /* @var $updatesVOstatus UpdatesVOstatus */
        echo "<div class='feed' data-status='{$updatesVOstatus->getStatusId()}'>";
        showStatus($updatesVOstatus,$updatesVOstatus->getAuthor());
        
        // Display comments
         if(isset($arr_allComments[$updatesVOstatus->getStatusId()])) {
             $arr_updatesVOcomment = $arr_allComments[$updatesVOstatus->getStatusId()];
             foreach($arr_updatesVOcomment as $updatesVOcomment)
             {
                 showComment($updatesVOcomment, $updatesVOstatus->getAuthor());
             }
        }
        
        showCommentForm();
        echo '</div>';
    }       
}
?>
