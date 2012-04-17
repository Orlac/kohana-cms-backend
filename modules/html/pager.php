<?php
namespace modules\html;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pager
 *
 * @author Antonio
 * orlac@rambler.ru
 */
class Pager {
    //put your code here
    private $url, $conn, $page_size = 10, $count ;
    public $errors;
    public $varSize = array(
        '5' => 5,
        '10' => 10,
        '20' => 20,
        '50' => 50,
    );
    public function __construct($conn, $url = null) {
        $this->url = ($url === null)? "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : $url;
        $this->conn = $conn;
    }
    
    public function setPageSize($s)
    {
        $this->page_size = $s;
    }
    
    protected function getPageSize()
    {
        return (isset($_GET['pSize']))? (int)$_GET['pSize'] : $this->page_size ;
    }
    
    public function setup($sql)
    {
        $tmp = $sql;
        //$sql = str_ireplace('from', ' , count(*) as count_rows from ', $sql);
        $sql = preg_replace('/^select(.*)from/is', ' select count(*) as count_rows from ', $sql);
        $this->page_size = $this->getPageSize();
        if($r = mysql_query($sql, $this->conn))
        {
            $data = mysql_fetch_row($r);
            $this->count = $data[0];
            /*$result = $this->conn->Execute($sql);
            $this->count = $result->fields['count_rows'];*/
            $active = $this->getActivePage();
            $tmp .= ' limit '.$active*$this->page_size.', './*($active+1)**/$this->page_size;
            return $tmp;
        }
        $this->errors = mysql_error($this->conn);
        return false;
    }
    
    private function getActivePage()
    {
        return (isset($_GET['pNum']))? (int)$_GET['pNum'] : 0;
    }
    
    public function render()
    {
        $start = max(0, ($this->getActivePage() - 10));
        $end = min( ($this->count/$this->page_size), ($this->getActivePage() + 10) );
        ?>
        <div id="pageNav">
            <ul>
        <?
        while( $start < $end )
        {
            ?>
              <li>
            <?
            if($start == $this->getActivePage())
            {
                ?>
                <div class="active"><? echo $start + 1; ?></div>
                <?
            }else
            {
                ?>
                <div>
                <a href="<?echo $this->getUrl($start)?>"><? echo $start + 1; ?></a>
                </div>
                <?
            }
            $start++;
            ?>
              </li>
            <?
        }
        ?>
            </ul>
        </div>
        <div id="pageSize">
            <ul>    
            <?
            foreach($this->varSize as $label => $size)
            {
                $link = $this->getUrl($this->getActivePage())
                ?>
            
                <li>
                    <div>
                        <a href="<?echo $this->getUrl($this->getActivePage(), $size) ?>"><? echo $label; ?></a>
                    </div>
                </li>
            
                <?
            }
            ?>
            </ul>    
        </div>
        <?
    }
    
    private function getUrl($page, $size = null)
    {
        $tmp = $this->url;
        $parse = parse_url($this->url);
        $fragment = (isset($parse['fragment']))? $parse['fragment'] : '';
        $tmp = str_ireplace($fragment, '', $tmp);
        if(!empty($parse['query']) )
        {
            $tmp = preg_replace('/&pNum=([0-9]{1,})/', '', $tmp);
            $tmp = preg_replace('/pNum=([0-9]{1,})&/', '', $tmp);
            $tmp = preg_replace('/&pSize=([0-9]{1,})/', '', $tmp);
            $tmp = preg_replace('/pSize=([0-9]{1,})&/', '', $tmp);
            $tmp .= '&pNum='.$page;
            
            
        }else
        {
            $tmp .= '?pNum='.$page;
        }
        $tmp .= '&pSize='.(($size != null)? $size : $this->page_size);
        $tmp .= ($fragment)? '#'.$fragment : '';
        return $tmp;
    }
    
}

?>
