<?php

/**
 *
 * @param UpdatesVOstatus $updatesVOstatus
 */
function showStatus($updatesVOstatus,$statusAuthor)
{
    $log = Log::getInstance();
    $log->debug('status author: ' . $statusAuthor);
    
    $canDelete = FALSE;

    // Checking author of the status
    if ($_SESSION['myid'] == $statusAuthor) {
        $canDelete = TRUE;
    }
    ?>
    <img class="userpic" src="<?php echo IMAGES; ?>profilepics/<?php echo $updatesVOstatus->getAuthor(); ?>.jpg" />
    <div class="status">
        <span class="author"><?php echo $updatesVOstatus->getDisplayname(); ?></span>
        <div class="quote">
            <?php echo $updatesVOstatus->getStatus(); ?>    
            <span class="date"><?php echo $updatesVOstatus->getDate(); ?></span>
            <?php
            if ($canDelete) {
                echo '<a class="delete"></a>';
            }
            ?>
        </div>
         <div class="like-details">
            <a class="like">
                    <?php
                    if ($updatesVOstatus->getILiked() == 1)
                        echo 'Unlike';
                    else
                        echo 'Like';
                    ?>
                </a>
                <span>
                <?php
                if ($updatesVOstatus->getILiked()) {
                    if ($updatesVOstatus->getNoOfLikes() == 1)
                        echo "You like this";
                    elseif ($updatesVOstatus->getNoOfLikes() > 1)
                        echo "You and ", $updatesVOstatus->getNoOfLikes()-1, " people like this";
                }
                elseif ($updatesVOstatus->getNoOfLikes() > 0)
                    echo "{$updatesVOstatus->getNoOfLikes()} people like this";
                ?>
                </span>
        </div>
        <div class="clearer"></div>
        </div>
    <?php

}
?>
