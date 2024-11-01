<?php
$json = file_get_contents('php://input');

$options         = get_option('inline_settings');
$token           = $options['inline_text_token'];
$post_types      = $options['inline_post_type'];
$inline_count    = $options['inline_count'];
$dimage          = $options['dimage'];
$post_type       = explode(',', $post_types);
$telegram        = urldecode($json);
$telegram        = str_replace('jason=', '', $telegram);
$myresults       = json_decode($telegram);
$update_id       = $myresults->update_id;
$inline_query    = $myresults->inline_query;
$inline_query_id = $inline_query->id;
$search           = $inline_query->query;

if ($inline_query_id) {
    
    $args = array(
        'post_type' => $post_type,
        's' => $search,
        'posts_per_page' => '10',
        'pagination' => false
    );
    
    // The Query
    $query   = new WP_Query($args);
    $results = array();
    $result  = array();
    // The Loop
    if ($query->have_posts()) {
        $i == 0;
        while ($query->have_posts()) { 
            $query->the_post();
            $inlinemessage          = $options['inline_template'];
            $inlinemessage          = str_replace('%TITLE%', get_the_title(), $inlinemessage);
            $inlinemessage          = str_replace('%Excerpt%', get_the_excerpt(), $inlinemessage);
            $inlinemessage          = str_replace('%LINK%', get_permalink(), $inlinemessage);
            $inlinemessage          = str_replace('%CONTENT%', get_the_content(), $inlinemessage);
            $inlinemessage          = str_replace('%SHORTLINK%', wp_get_shortlink(), $inlinemessage);
            $result['type']         = 'article';
            $result['id']           = 'article' . get_the_ID();
            $result['title']        = get_the_title();
            //$result['message_text'] = get_the_title().chr(10).get_the_excerpt().chr(10).wp_get_shortlink();
            $result['message_text'] = $inlinemessage;
            $result['url']          = get_permalink();
            $result['hide_url']     = true;
            $result['description']  = get_the_excerpt();
            if (has_post_thumbnail()) {
                $result['thumb_url'] = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
            } else {
                if (!$dimage == '') {
                    $result['thumb_url'] = $dimage;
                }
            }
            $results[] = $result;
            $i++;
        }
        //$final['results'] =$results ;
        $encodedresults = json_encode($results);
    } else {
        $result['type']         = 'article';
        $result['id']           = 'articlewb22';
        $inline_noresult        = $options['inline_noresult'];
        $inline_noresultdesc    = $options['inline_noresultdesc'];
        $inline_noresultmessage = $options['inline_noresultmessage'];
        
        $result['title']        = $inline_noresult;
        $result['message_text'] = $inline_noresultmessage;
        $result['url']          = get_bloginfo('url');
        $result['hide_url']     = true;
        $result['description']  = $options['inline_noresultdesc'];
        if (!$dimage == '') {
            $result['thumb_url'] = $dimage;
        }
        $results[]      = $result;
        $encodedresults = json_encode($results);
    }
    
    $url     = 'https://api.telegram.org/bot' . $token . '/answerInlineQuery';
    $data    = array(
        'inline_query_id' => $inline_query_id,
        'results' => $encodedresults
    );
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    
    $context = stream_context_create($options);
    $update  = file_get_contents($url, false, $context);
} else {
    
    $chosen_inline_result = $myresults->chosen_inline_result;
    if ($chosen_inline_result) {
        if ($inline_count == 'yes') {
            
            $result_id   = $chosen_inline_result->result_id;
            $result_id   = str_replace('article', '', $result_id);
            $searchcount = get_post_meta($result_id, 'searchcount', true);
            if ($searchcount == '') {
                update_post_meta($result_id, 'searchcount', 1);
            } else {
                $searchcount++;
                update_post_meta($result_id, 'searchcount', $searchcount);
            }
        }
    } else {
        
        
        $options             = get_option('inline_settings');
        $inline_directanswer = $options['inline_directanswer'];
        $token               = $options['inline_text_token'];
        if (!$inline_directanswer == '') {
            $messages = $inline_directanswer;
            $message  = $myresults->message;
            $from     = $message->from;
            $user_id  = $from->id;
            $url      = 'https://api.telegram.org/bot' . $token . '/sendMessage';
            $data     = array(
                'chat_id' => $user_id,
                'text' => $messages,
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => false
            );
            $options  = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data)
                )
            );
            
            $context = stream_context_create($options);
            $update  = @file_get_contents($url, false, $context);
            
        }
    }
    
}
?>