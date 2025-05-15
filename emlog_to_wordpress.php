<?php
/*
Plugin Name: Emlog To WordPress Exporter
Description: 导出 Emlog 数据为 WordPress 可导入的 XML（WXR）格式。
Author: 九笙
Author URI: https://xfsay.com
Version: 1.0
*/

!defined('EMLOG_ROOT') && exit('access denied!');

function plugin_setting_view() {
    echo '<div class="alert alert-info">点击下方按钮导出 WordPress 数据文件</div>';
    echo '<form method="post"><input type="submit" name="export_wp" value="导出 WordPress XML" class="btn btn-primary"></form>';
    
    if (isset($_POST['export_wp'])) {
        export_to_wordpress();
    }
}

function export_to_wordpress() {
    global $CACHE;
    $db = MySql::getInstance();

    header('Content-type: text/xml');
    header('Content-Disposition: attachment; filename=wordpress-export.xml');

    echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
    echo '<rss version="2.0"
        xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:wp="http://wordpress.org/export/1.2/"
    >';
    echo '<channel>';
    echo '<title>' . Option::get('blogname') . '</title>';
    echo '<link>' . BLOG_URL . '</link>';
    echo '<description>' . Option::get('bloginfo') . '</description>';
    echo '<language>zh-CN</language>';
    echo '<wp:wxr_version>1.2</wp:wxr_version>';
    echo '<wp:base_site_url>' . BLOG_URL . '</wp:base_site_url>';
    echo '<wp:base_blog_url>' . BLOG_URL . '</wp:base_blog_url>';

    echo '<wp:author>';
    echo '<wp:author_id>1</wp:author_id>';
    echo '<wp:author_login><![CDATA[admin]]></wp:author_login>';
    echo '<wp:author_email><![CDATA[email@example.com]]></wp:author_email>';
    echo '<wp:author_display_name><![CDATA[admin]]></wp:author_display_name>';
    echo '<wp:author_first_name><![CDATA[]]></wp:author_first_name>';
    echo '<wp:author_last_name><![CDATA[]]></wp:author_last_name>';
    echo '</wp:author>';

    $sorts = $CACHE->readCache('sort');
    foreach ($sorts as $sid => $sort) {
        echo '<wp:category>';
        echo '<wp:term_id>' . $sid . '</wp:term_id>';
        echo '<wp:category_nicename>' . $sort['alias'] . '</wp:category_nicename>';
        echo '<wp:cat_name><![CDATA[' . $sort['sortname'] . ']]></wp:cat_name>';
        echo '</wp:category>';
    }

    $tags = $CACHE->readCache('tags');
    foreach ($tags as $tid => $tag) {
        echo '<wp:tag>';
        echo '<wp:term_id>' . $tid . '</wp:term_id>';
        echo '<wp:tag_slug>' . $tag['tagname'] . '</wp:tag_slug>';
        echo '<wp:tag_name><![CDATA[' . $tag['tagname'] . ']]></wp:tag_name>';
        echo '</wp:tag>';
    }

    $result = $db->query("SELECT * FROM " . DB_PREFIX . "blog ORDER BY date DESC");
    while ($row = $db->fetch_array($result)) {
        $post_type = $row['type'] == 'page' ? 'page' : 'post';
        echo '<item>';
        echo '<title><![CDATA[' . $row['title'] . ']]></title>';
        echo '<link>' . Url::log($row['gid']) . '</link>';
        echo '<pubDate>' . gmdate('D, d M Y H:i:s', $row['date']) . ' +0000</pubDate>';
        echo '<dc:creator><![CDATA[admin]]></dc:creator>';
        echo '<guid isPermaLink="false">' . Url::log($row['gid']) . '</guid>';
        echo '<description></description>';
        echo '<content:encoded><![CDATA[' . $row['content'] . ']]></content:encoded>';
        echo '<excerpt:encoded><![CDATA[' . $row['excerpt'] . ']]></excerpt:encoded>';
        echo '<wp:post_id>' . $row['gid'] . '</wp:post_id>';
        echo '<wp:post_date>' . date('Y-m-d H:i:s', $row['date']) . '</wp:post_date>';
        echo '<wp:post_type>' . $post_type . '</wp:post_type>';
        echo '<wp:status>' . ($row['hide'] == 'y' ? 'draft' : 'publish') . '</wp:status>';

        if ($post_type == 'post' && isset($sorts[$row['sortid']])) {
            echo '<category domain="category" nicename="' . $sorts[$row['sortid']]['alias'] . '"><![CDATA[' . $sorts[$row['sortid']]['sortname'] . ']]></category>';
        }

        if ($post_type == 'post' && $row['tag']) {
            $post_tags = explode(',', $row['tag']);
            foreach ($post_tags as $t) {
                echo '<category domain="post_tag" nicename="' . $tags[$t]['tagname'] . '"><![CDATA[' . $tags[$t]['tagname'] . ']]></category>';
            }
        }

        $comments = $db->query("SELECT * FROM " . DB_PREFIX . "comment WHERE gid={$row['gid']} AND hide='n'");
        while ($c = $db->fetch_array($comments)) {
            echo '<wp:comment>';
            echo '<wp:comment_id>' . $c['cid'] . '</wp:comment_id>';
            echo '<wp:comment_author><![CDATA[' . $c['poster'] . ']]></wp:comment_author>';
            echo '<wp:comment_author_email><![CDATA[' . $c['mail'] . ']]></wp:comment_author_email>';
            echo '<wp:comment_author_url><![CDATA[' . $c['url'] . ']]></wp:comment_author_url>';
            echo '<wp:comment_author_IP><![CDATA[' . $c['ip'] . ']]></wp:comment_author_IP>';
            echo '<wp:comment_date>' . date('Y-m-d H:i:s', $c['date']) . '</wp:comment_date>';
            echo '<wp:comment_content><![CDATA[' . $c['comment'] . ']]></wp:comment_content>';
            echo '<wp:comment_approved>1</wp:comment_approved>';
            echo '<wp:comment_type></wp:comment_type>';
            echo '<wp:comment_parent>0</wp:comment_parent>';
            echo '<wp:comment_user_id>0</wp:comment_user_id>';
            echo '</wp:comment>';
        }

        echo '</item>';
    }

    $links = $db->query("SELECT * FROM " . DB_PREFIX . "link");
    while ($link = $db->fetch_array($links)) {
        echo '<item>';
        echo '<title><![CDATA[' . $link['sitename'] . ']]></title>';
        echo '<link>' . $link['siteurl'] . '</link>';
        echo '<description><![CDATA[' . $link['description'] . ']]></description>';
        echo '<wp:post_type>link</wp:post_type>';
        echo '</item>';
    }

    echo '</channel>';
    echo '</rss>';
    exit;
}
