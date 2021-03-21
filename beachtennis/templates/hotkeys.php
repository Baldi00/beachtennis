<?php

function addButton($link) {
    echo '<a href="'.$link.'">';
    include 'gui/buttons/button_add.html';
    echo '</a>';
}

function editButton($link) {
    echo '<a href="'.$link.'">';
    include_once 'gui/buttons/button_edit.html';
    echo '</a>';
}

function deleteButton($link) {
    echo '<a href="'.$link.'">';
    include_once 'gui/buttons/button_delete.html';
    echo '</a>';
}

function exportButton($link) {
    echo '<a href="'.$link.'">';
    include_once 'gui/buttons/button_export.html';
    echo '</a>';
}

