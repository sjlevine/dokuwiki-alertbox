<?php
/**
 * Plugin Alertbox: Use bootstrap-style alerts.
 * 
 * @author     Steve Levine (sjlevine29@gmail.com)
 */
 
// must be run within DokuWiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
class syntax_plugin_alertbox extends DokuWiki_Syntax_Plugin {
 
    function getType(){ return 'container'; }
    function getAllowedTypes() { return array('formatting', 'substition', 'disabled'); }   
    function getSort(){ return 200; }
    function connectTo($mode) { $this->Lexer->addEntryPattern('<alert.*?>(?=.*?</alert>)',$mode,'plugin_alertbox'); }
    function postConnect() { $this->Lexer->addExitPattern('</alert>','plugin_alertbox'); }
 

    function handle($match, $state, $pos, &$handler){
        switch ($state) {
          case DOKU_LEXER_ENTER :
                // Default
                $alerttype = "alert-info";
                if (strpos($match, 'warning') != false) {
                    $alerttype = "alert-warning";
                } elseif (strpos($match, 'info') != false) {
                    $alerttype = "alert-info";
                } elseif (strpos($match, 'danger') != false) {
                    $alerttype = "alert-danger";
                } elseif (strpos($match, 'success') != false) {
                    $alerttype = "alert-success";
                }
                return array($state, $alerttype);
 
          case DOKU_LEXER_UNMATCHED :  return array($state, $match);
          case DOKU_LEXER_EXIT :       return array($state, '');
        }
        return array();
    }
 

    function render($mode, &$renderer, $data) {
        if($mode == 'xhtml'){
            list($state, $match) = $data;
            switch ($state) {
              case DOKU_LEXER_ENTER :      
                $renderer->doc .= "<div class='alert $match'>"; 
                break;
 
              case DOKU_LEXER_UNMATCHED :  $renderer->doc .= $renderer->_xmlEntities($match); break;
              case DOKU_LEXER_EXIT :       $renderer->doc .= "</div>"; break;
            }
            return true;
        }
        return false;
    }
 
}

?>
