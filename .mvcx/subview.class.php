<?php
class SubView extends View {
    private $parent =  NULL;
    private $matchString =  '';

    public function setParent(View $parent) {
        $this->parent = $parent;
    }

    public function setMatchStr($match) {
        $this->matchString = $match;
    }

    public function getMatchStr() {
        return $this->matchString;
    }
}
