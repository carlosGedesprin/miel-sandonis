<?php

namespace src\util;

//https://www.binpress.com/tutorial/custom-pagination-in-php-and-symfony/117

class paginator
{
    private $totalPages;
    private $page;
    private $rpp;

    private $myfile;

    public function __construct($page, $totalcount, $rpp)
    {
        $this->rpp=$rpp;
        $this->page=$page;

        $this->totalPages=$this->setTotalPages($totalcount, $rpp);

//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/paginator_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = 'paginator '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);


    }

    /*
     * var recCount: the total count of records
     * var $rpp: the record per page
     */

    private function setTotalPages($totalcount, $rpp)
    {
//$txt = 'paginator '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        if ($rpp == 0)
        {
            $rpp = 20; // In case we did not provide a number for $rpp
        }

        $this->totalPages=ceil($totalcount / $rpp);
        return $this->totalPages;
    }

    public function getTotalPages()
    {
//$txt = 'paginator '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        return $this->totalPages;
    }

    public function pagesOnPagination($page, $reverse_count = false, $ignore_on_page = false)
    {
//$txt = 'paginator '.__FUNCTION__.' start ======================================'.PHP_EOL; fwrite($this->myfile, $txt);

        $pagination_list = array();
        $u_previous_page = $u_next_page = '';

        if ($this->totalPages > 1)
        {
            if ($reverse_count)
            {
                $start_page = ($this->totalPages > 5) ? $this->totalPages - 4 : 1;
                $end_page = $this->totalPages;
            }
            else
            {
                // What we're doing here is calculating what the "start" and "end" pages should be. We
                // do this by assuming pagination is "centered" around the currently active page with
                // the three previous and three next page links displayed. Anything more than that and
                // we display the ellipsis, likewise anything less.
                //
                // $start_page is the page at which we start creating the list. When we have five or less
                // pages we start at page 1 since there will be no ellipsis displayed. Anymore than that
                // and we calculate the start based on the active page. This is the min/max calculation.
                // First (max) would we end up starting on a page less than 1? Next (min) would we end
                // up starting so close to the end that we'd not display our minimum number of pages.
                //
                // $end_page is the last page in the list to display. Like $start_page we use a min/max to
                // determine this number. Again at most five pages? Then just display them all. More than
                // five and we first (min) determine whether we'd end up listing more pages than exist.
                // We then (max) ensure we're displaying the minimum number of pages.
                $start_page = ($this->totalPages > 5) ? min(max(1, $page - 2), $this->totalPages - 4) : 1;
                $end_page = ($this->totalPages > 5) ? max(min($this->totalPages, $page + 2), 5) : $this->totalPages;
            }

            if ($page != 1)
            {
                //$u_previous_page = $this->generate_page_link($base_url, $page - 1, $start_name, $per_page);
                $u_previous_page = $page - 1;

                $pagination_list[] = array(
                    'page_number'	=> '',
                    'page_url'		=> $u_previous_page,
                    's_is_current'	=> false,
                    's_is_prev'		=> true,
                    's_is_next'		=> false,
                    's_is_ellipsis'	=> false,
                );
            }
            // This do...while exists purely to negate the need for start and end assign_block_vars, i.e.
            // to display the first and last page in the list plus any ellipsis. We use this loop to jump
            // around a little within the list depending on where we're starting (and ending).
            $at_page = 1;
            do
            {
                // We decide whether to display the ellipsis during the loop. The ellipsis is always
                // displayed as either the second or penultimate item in the list. So are we at either
                // of those points and of course do we even need to display it, i.e. is the list starting
                // on at least page 3 and ending three pages before the final item.
                $pagination_list[] = array(
                    'page_number'	=> $at_page,
                    //'page_url'		=> $this->generate_page_link($base_url, $at_page, $start_name, $per_page),
                    'page_url'		=> $at_page,
                    's_is_current'	=> (!$ignore_on_page && $at_page == $page),
                    's_is_prev'		=> false,
                    's_is_next'		=> false,
                    's_is_ellipsis'	=> ($at_page == 2 && $start_page > 2) || ($at_page == $this->totalPages - 1 && $end_page < $this->totalPages - 1),
                );

                // We may need to jump around in the list depending on whether we have or need to display
                // the ellipsis. Are we on page 2 and are we more than one page away from the start
                // of the list? Yes? Then we jump to the start of the list. Likewise are we at the end of
                // the list and are there more than two pages left in total? Yes? Then jump to the penultimate
                // page (so we can display the ellipsis next pass). Else, increment the counter and keep
                // going
                if ($at_page == 2 && $at_page < $start_page - 1)
                {
                    $at_page = $start_page;
                }
                else if ($at_page == $end_page && $end_page < $this->totalPages - 1)
                {
                    $at_page = $this->totalPages - 1;
                }
                else
                {
                    $at_page++;
                }
            }
            while ($at_page <= $this->totalPages);

            if ($page != $this->totalPages)
            {
                //$u_next_page = $this->generate_page_link($base_url, $page + 1, $start_name, $per_page);
                $u_next_page = $page + 1;

                $pagination_list[] = array(
                    'page_number'	=> '',
                    'page_url'		=> $u_next_page,
                    's_is_current'	=> false,
                    's_is_prev'		=> false,
                    's_is_next'		=> true,
                    's_is_ellipsis'	=> false,
                );
            }
        }

        return $pagination_list;
    }

}
