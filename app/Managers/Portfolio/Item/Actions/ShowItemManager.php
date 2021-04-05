<?php


namespace App\Managers\Portfolio\Item\Actions;


use App\Managers\Portfolio\Item\BaseItemManager;
use App\Models\WpPost;
use App\Repositories\WpBwgGalleryRepository;
use App\Repositories\WpTermRepository;
use Illuminate\Database\Eloquent\Collection;

class ShowItemManager extends BaseItemManager
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var string|null
     */
    private $galleryShortcode = null;

    /**
     * @var WpTermRepository
     */
    private $wpTermRepository;

    /**
     * @var WpBwgGalleryRepository
     */
    private $wpBwgGalleryRepository;

    /**
     * ShowItemManager constructor.
     *
     * @param string $slug
     */
    public function __construct($slug)
    {
        parent::__construct();

        $this->slug = $slug;
        $this->wpTermRepository = app(WpTermRepository::class);
        $this->wpBwgGalleryRepository = app(WpBwgGalleryRepository::class);
    }

    /**
     * Get portfolio item by slug.
     *
     * @param string $slug
     * @return WpPost
     */
    public function getItemBySlug()
    {
        $item = $this->wpPostRepository->getSpecifiedBySlug($this->slug);

        if($item){
            $item->post_content = $this->separateShortcode($item->post_content);
        }

        return $item;
    }

    /**
     * Separate Wordpress Shortcode code from content.
     *
     * @param string $content
     * @return string
     */
    private function separateShortcode($content)
    {
        $contentLength = iconv_strlen($content, $this->charset);
        $shortcodeStart = iconv_strpos($content, '[Best_Wordpress_Gallery', 0, $this->charset);
        if( $shortcodeStart === false ) return $content;
        $shortcodeEnd = iconv_strpos($content, ']', $shortcodeStart, $this->charset);

        $this->galleryShortcode = iconv_substr($content, $shortcodeStart, $shortcodeEnd-$shortcodeStart+1, $this->charset);

        $beforeShortcode = iconv_substr($content, 0, $shortcodeStart, $this->charset);
        $afterShortcode = iconv_substr($content, $shortcodeEnd+1, $contentLength-$shortcodeEnd-1, $this->charset);
        $content = $beforeShortcode . $afterShortcode;
        $content = $this->wpautop($content);

        return $content;
    }

    /**
     * Check if portfolio item corresponds to the current app locale.
     *
     * @param WpPost $item
     * @return WpPost|bool
     */
    public function localizedFound($item)
    {
        if( ! in_array($item->ID, $this->translatedPostsIds) ){

            $translatedItemId = false;

            foreach ($this->translations as $translation) {
                $post_translations = unserialize($translation->description);
                if(in_array($item->ID, $post_translations)){
                    if( isset($post_translations[$this->locale]) ){
                        $translatedItemId = $post_translations[$this->locale];
                        break;
                    }
                }
            }

            if( ! $translatedItemId ){
                return false;
            }

            $item = $this->wpPostRepository->getSpecifiedById($translatedItemId);

            return $item;
        }

        return false;
    }

    /**
     * Get item taxonomy (categories, tags).
     *
     * @param string $taxonomyName
     * @param WpPost $post
     * @return string
     */
    public function getTaxonomy($taxonomyName, $post)
    {
        $taxonomies = $post->taxonomies;
        $taxonomyItems = [];

        foreach ($taxonomies as $taxonomy) {
            if($taxonomy->taxonomy == $taxonomyName){
                $category = $this->wpTermRepository->getTermById($taxonomy->term_id);
                $taxonomyItems[] = $category->name;
            }
        }

        $taxonomyItems = implode(', ', $taxonomyItems);

        return $taxonomyItems;
    }

    /**
     * Get BWG gallery images.
     *
     * @return Collection|null
     */
    public function getGalleryImages()
    {
        if($this->galleryShortcode === null){
            return null;
        }

        $needleStart = 'gal_title="';
        $needleEnd = '"';
        $needleLength = iconv_strlen($needleStart, $this->charset);
        $galleryIdPositionStart = iconv_strpos($this->galleryShortcode, $needleStart, 0, $this->charset);
        if( $galleryIdPositionStart === false ) return null;
        $galleryIdPositionEnd = iconv_strpos($this->galleryShortcode, $needleEnd, $galleryIdPositionStart+$needleLength, $this->charset);
        $galleryTitle = iconv_substr(
            $this->galleryShortcode,
            $galleryIdPositionStart+$needleLength,
            $galleryIdPositionEnd-$galleryIdPositionStart-$needleLength,
            $this->charset
        );

        if(!$galleryTitle){
            return null;
        }

        $gallery = $this->wpBwgGalleryRepository->getGallery($galleryTitle);

        $images = $gallery->images;

        return $images ?: null;
    }

    /**
     * Get previous portfolio item.
     *
     * @param WpPost $currentPost
     * @return WpPost
     */
    public function getPreviousItem($currentPost)
    {
        $previousItem = $this->wpPostRepository->getPreviousPost($currentPost, $this->translatedPostsIds);

        return $previousItem;
    }

    /**
     * Get next portfolio item.
     *
     * @param WpPost $currentPost
     * @return WpPost
     */
    public function getNextItem($currentPost)
    {
        $nextItem = $this->wpPostRepository->getNextPost($currentPost, $this->translatedPostsIds);

        return $nextItem;
    }

    /**
     * Function from WordPress core.
     * Replaces double line breaks with paragraph elements.
     *
     * @param $pee
     * @param bool $br
     * @return string|string[]|null
     */
    private function wpautop( $pee, $br = true ) {
        $pre_tags = array();

        if ( trim( $pee ) === '' ) {
            return '';
        }

        // Just to make things a little easier, pad the end.
        $pee = $pee . "\n";

        /*
         * Pre tags shouldn't be touched by autop.
         * Replace pre tags with placeholders and bring them back after autop.
         */
        if ( strpos( $pee, '<pre' ) !== false ) {
            $pee_parts = explode( '</pre>', $pee );
            $last_pee  = array_pop( $pee_parts );
            $pee       = '';
            $i         = 0;

            foreach ( $pee_parts as $pee_part ) {
                $start = strpos( $pee_part, '<pre' );

                // Malformed HTML?
                if ( false === $start ) {
                    $pee .= $pee_part;
                    continue;
                }

                $name              = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[ $name ] = substr( $pee_part, $start ) . '</pre>';

                $pee .= substr( $pee_part, 0, $start ) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }
        // Change multiple <br>'s into two line breaks, which will turn into paragraphs.
        $pee = preg_replace( '|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee );

        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

        // Add a double line break above block-level opening tags.
        $pee = preg_replace( '!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee );

        // Add a double line break below block-level closing tags.
        $pee = preg_replace( '!(</' . $allblocks . '>)!', "$1\n\n", $pee );

        // Add a double line break after hr tags, which are self closing.
        $pee = preg_replace( '!(<hr\s*?/?>)!', "$1\n\n", $pee );

        // Standardize newline characters to "\n".
        $pee = str_replace( array( "\r\n", "\r" ), "\n", $pee );

        // Find newlines in all elements and add placeholders.
        $pee = $this->wp_replace_in_html_tags( $pee, array( "\n" => ' <!-- wpnl --> ' ) );

        // Collapse line breaks before and after <option> elements so they don't get autop'd.
        if ( strpos( $pee, '<option' ) !== false ) {
            $pee = preg_replace( '|\s*<option|', '<option', $pee );
            $pee = preg_replace( '|</option>\s*|', '</option>', $pee );
        }

        /*
         * Collapse line breaks inside <object> elements, before <param> and <embed> elements
         * so they don't get autop'd.
         */
        if ( strpos( $pee, '</object>' ) !== false ) {
            $pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
            $pee = preg_replace( '|\s*</object>|', '</object>', $pee );
            $pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
        }

        /*
         * Collapse line breaks inside <audio> and <video> elements,
         * before and after <source> and <track> elements.
         */
        if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
            $pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
            $pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
            $pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
        }

        // Collapse line breaks before and after <figcaption> elements.
        if ( strpos( $pee, '<figcaption' ) !== false ) {
            $pee = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $pee );
            $pee = preg_replace( '|</figcaption>\s*|', '</figcaption>', $pee );
        }

        // Remove more than two contiguous line breaks.
        $pee = preg_replace( "/\n\n+/", "\n\n", $pee );

        // Split up the contents into an array of strings, separated by double line breaks.
        $pees = preg_split( '/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY );

        // Reset $pee prior to rebuilding.
        $pee = '';

        // Rebuild the content as a string, wrapping every bit with a <p>.
        foreach ( $pees as $tinkle ) {
            $pee .= '<p>' . trim( $tinkle, "\n" ) . "</p>\n";
        }

        // Under certain strange conditions it could create a P of entirely whitespace.
        $pee = preg_replace( '|<p>\s*</p>|', '', $pee );

        // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
        $pee = preg_replace( '!<p>([^<]+)</(div|address|form)>!', '<p>$1</p></$2>', $pee );

        // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
        $pee = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee );

        // In some cases <li> may get wrapped in <p>, fix them.
        $pee = preg_replace( '|<p>(<li.+?)</p>|', '$1', $pee );

        // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
        $pee = preg_replace( '|<p><blockquote([^>]*)>|i', '<blockquote$1><p>', $pee );
        $pee = str_replace( '</blockquote></p>', '</p></blockquote>', $pee );

        // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
        $pee = preg_replace( '!<p>\s*(</?' . $allblocks . '[^>]*>)!', '$1', $pee );

        // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
        $pee = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*</p>!', '$1', $pee );

        // Optionally insert line breaks.
        if ( $br ) {
            // Replace newlines that shouldn't be touched with a placeholder.
            $pee = preg_replace_callback( '/<(script|style|svg).*?<\/\\1>/s', [$this, '_autop_newline_preservation_helper'], $pee );

            // Normalize <br>
            $pee = str_replace( array( '<br>', '<br/>' ), '<br />', $pee );

            // Replace any new line characters that aren't preceded by a <br /> with a <br />.
            $pee = preg_replace( '|(?<!<br />)\s*\n|', "<br />\n", $pee );

            // Replace newline placeholders with newlines.
            $pee = str_replace( '<WPPreserveNewline />', "\n", $pee );
        }

        // If a <br /> tag is after an opening or closing block tag, remove it.
        $pee = preg_replace( '!(</?' . $allblocks . '[^>]*>)\s*<br />!', '$1', $pee );

        // If a <br /> tag is before a subset of opening or closing block tags, remove it.
        $pee = preg_replace( '!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee );
        $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

        // Replace placeholder <pre> tags with their original content.
        if ( ! empty( $pre_tags ) ) {
            $pee = str_replace( array_keys( $pre_tags ), array_values( $pre_tags ), $pee );
        }

        // Restore newlines in all elements.
        if ( false !== strpos( $pee, '<!-- wpnl -->' ) ) {
            $pee = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $pee );
        }

        return $pee;
    }

    /**
     * Function from WordPress core.
     * Replace characters or phrases within HTML elements only.
     *
     * @param $haystack
     * @param $replace_pairs
     * @return string
     */
    private function wp_replace_in_html_tags( $haystack, $replace_pairs ) {
        // Find all elements.
        $textarr = $this->wp_html_split( $haystack );
        $changed = false;

        // Optimize when searching for one item.
        if ( 1 === count( $replace_pairs ) ) {
            // Extract $needle and $replace.
            foreach ( $replace_pairs as $needle => $replace ) {
            }

            // Loop through delimiters (elements) only.
            for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
                if ( false !== strpos( $textarr[ $i ], $needle ) ) {
                    $textarr[ $i ] = str_replace( $needle, $replace, $textarr[ $i ] );
                    $changed       = true;
                }
            }
        } else {
            // Extract all $needles.
            $needles = array_keys( $replace_pairs );

            // Loop through delimiters (elements) only.
            for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
                foreach ( $needles as $needle ) {
                    if ( false !== strpos( $textarr[ $i ], $needle ) ) {
                        $textarr[ $i ] = strtr( $textarr[ $i ], $replace_pairs );
                        $changed       = true;
                        // After one strtr() break out of the foreach loop and look at next element.
                        break;
                    }
                }
            }
        }

        if ( $changed ) {
            $haystack = implode( $textarr );
        }

        return $haystack;
    }

    /**
     * Function from WordPress core.
     * Separate HTML elements and comments from the text.
     *
     * @param $input
     * @return array|false|string[]
     */
    private function wp_html_split( $input ) {
        return preg_split( $this->get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
    }

    /**
     * Function from WordPress core.
     * Retrieve the regular expression for an HTML element.
     *
     * @return string
     */
    private function get_html_split_regex() {
        static $regex;

        if ( ! isset( $regex ) ) {
            // phpcs:disable Squiz.Strings.ConcatenationSpacing.PaddingFound -- don't remove regex indentation
            $comments =
                '!'             // Start of comment, after the <.
                . '(?:'         // Unroll the loop: Consume everything until --> is found.
                .     '-(?!->)' // Dash not followed by end of comment.
                .     '[^\-]*+' // Consume non-dashes.
                . ')*+'         // Loop possessively.
                . '(?:-->)?';   // End of comment. If not found, match all input.

            $cdata =
                '!\[CDATA\['    // Start of comment, after the <.
                . '[^\]]*+'     // Consume non-].
                . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
                .     '](?!]>)' // One ] not followed by end of comment.
                .     '[^\]]*+' // Consume non-].
                . ')*+'         // Loop possessively.
                . '(?:]]>)?';   // End of comment. If not found, match all input.

            $escaped =
                '(?='             // Is the element escaped?
                .    '!--'
                . '|'
                .    '!\[CDATA\['
                . ')'
                . '(?(?=!-)'      // If yes, which type?
                .     $comments
                . '|'
                .     $cdata
                . ')';

            $regex =
                '/('                // Capture the entire match.
                .     '<'           // Find start of element.
                .     '(?'          // Conditional expression follows.
                .         $escaped  // Find end of escaped element.
                .     '|'           // ...else...
                .         '[^>]*>?' // Find end of normal element.
                .     ')'
                . ')/';
            // phpcs:enable
        }

        return $regex;
    }

    /**
     * Function from WordPress core.
     * Newline preservation help function for wpautop.
     *
     * @param $matches
     * @return string|string[]
     */
    private function _autop_newline_preservation_helper( $matches ) {
        return str_replace( "\n", '<WPPreserveNewline />', $matches[0] );
    }
}