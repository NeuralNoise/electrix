<?php

/**
 *
 * @param UpdatesVOComment $updatesVOcomment
 */
function showComment($updatesVOcomment, $statusAuthor) {
    $log = Log::getInstance();
    $log->debug('status author: ' . $statusAuthor);

    $canDelete = FALSE;

    // Checking author of the comment
    if (($_SESSION['myid'] ) == $updatesVOcomment->getAuthor()) {
        $canDelete = TRUE;
    }

    // Checking author of status
    if ($_SESSION['myid'] == $statusAuthor) {
        $canDelete = TRUE;
    }   
 
    ?>
    <div class="comment-wrapper" data-comment="<?php echo $updatesVOcomment->getCommentId(); ?>">
        <img class="userpic" src="<?php echo IMAGES; ?>profilepics/<?php echo $updatesVOcomment->getAuthor(); ?>.jpg" />
        <div class="comment">
            <span class="author"><?php echo $updatesVOcomment->getDisplayname(); ?></span>
            <div class="quote">
                <?php echo $updatesVOcomment->getComment(); ?>
                <span class="date">at <?php echo $updatesVOcomment->getDate(); ?></span>
                <?php
                if ($canDelete) {
                    echo '<a class="delete"> </a>';
                }
                ?>
            </div>
            <div class="like-details">
                <a class="like">
                    <?php
                    if ($updatesVOcomment->getILiked() == 1)
                        echo 'Unlike';
                    else
                        echo 'Like';
                    ?>
                </a>
                <span>
                <?php
                if ($updatesVOcomment->getILiked()) {
                    if ($updatesVOcomment->getNoOfLikes() == 1)
                        echo "You like this";
                    elseif ($updatesVOcomment->getNoOfLikes() > 1)
                        echo "You and ", $updatesVOcomment->getNoOfLikes()-1, " people like this";
                }
                elseif ($updatesVOcomment->getNoOfLikes() > 0)
                    echo "{$updatesVOcomment->getNoOfLikes()} people like this";
                ?>
                </span>
            </div>
        </div>
        <div class="clearer"></div>
    </div>
    <?php
}

function showCommentForm() {
    ?>
    <div class="new-comment"></div>

    <div class="comment-box">
        <textarea name="comment"></textarea>
        <input class="button" type="button" value="Comment"/>
    </div>
    <?php
}
?>
