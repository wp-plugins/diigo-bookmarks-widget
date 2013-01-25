<style>
    .<?php echo get_class(); ?>_title .<?php echo get_class(); ?>_titleLink{
        color: #0044cc;
        text-decoration:none;
        border-bottom:1px solid #C2D3F2;
    }
    .<?php echo get_class(); ?>_title .<?php echo get_class(); ?>_titleLink:hover{
        text-decoration:none;
        border-bottom:1px solid #0044cc;
    }
    .<?php echo get_class(); ?>_userLink{
        color:#7777cc;
    }
    
</style>
<?php
echo $before_widget;
$title = ( $title ) ? $title : 'Latest Diigo Bookmarks';
echo $before_title . $title . $after_title;
echo '<ul class = "'.  get_class().'_widget">';
foreach ($bookmarks as $item):
    ?>
    <li>  
        <span class="<?php echo get_class(); ?>_title">
            <a class="<?php echo get_class(); ?>_titleLink" href="<?php echo $item->url; ?>"><?php echo $item->title ?></a>
            <br/><span class="<?php echo get_class(); ?>_date"><?php $date=  explode(' ', $item->created_at); echo $date[0]; ?></span> By <a class="<?php echo get_class(); ?>_userLink" href="http://www.diigo.com/user/<?php echo $item->user;?>"><?php echo $item->user;?></a>
        </span><!--title-->
    </li>
    <?php
endforeach;
echo '</ul>';
echo $after_widget;
?>
