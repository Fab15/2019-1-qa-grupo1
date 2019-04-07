<?php

// Requires the connection and all the dependencies.
require_once("connection.php");
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/EdgeHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Edge.php";

// Uses the functions of ArangoDB.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\Edge;
use ArangoDBClient\GraphHandler;
use ArangoDBClient\Vertex;

// This function gets two parameters and associates them in an edge.
// They must be their usernames.
function userFollow($fromUser, $toUser){

    // All the variables that we need to manage the function.
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);

    // We create two cursor to make the consults with the data.
    $cursorFrom = $collectionHandler->byExample('user', ['username' => $fromUser]);
    $cursorTo   = $collectionHandler->byExample('user', ['username' => $toUser]);

    // Now, we get the documents iterating over the cursors.
    $idFromUser         = null;
    $idToUser           = null;
    $resultingDocument  = array();

    foreach ($cursorFrom as $key => $value) {
        $resultingDocument[$key] = $value;

        // Gets the id of the FromUser.
        $idFromUser = $resultingDocument[$key]->getHandle();
    }

    foreach ($cursorTo as $key => $value) {
        $resultingDocument[$key] = $value;

        // Gets the id of the ToUser.
        $idToUser = $resultingDocument[$key]->getHandle();
    }

    //$graphHandler->saveVertex("MYSS", ['12345','azzefj'], 'user');

    // Now make an edge between them.
    $edgeInfo   = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge('follows', $idFromUser, $idToUser, $linkBetween);

}

function connectTag($idPost, $Tag){

    $connection = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);

    $idTag = null;
    $resultingDocument = null;

    $cursorTag   = $collectionHandler->byExample('tag', ['name' => $Tag]);

    if($cursorTag->getCount() == 0){
        //crear el Tag nuevo
    }

    foreach ($cursorTag as $key => $value) {
        $resultingDocument[$key] = $value;

        // Gets the id of the FromUser.
        $idTag = $resultingDocument[$key]->getHandle();
    }

    $edgeInfo   = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge('has_tag', $idPost, $idTag, $linkBetween);

}

function userPosted($idUser, $idPost){
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);

    $cursorFrom = $collectionHandler->byExample('user', ['username' => $idUser]);
    $cursorTo   = $collectionHandler->byExample('post', ['title' => $idPost]);

    // Now, we get the documents iterating over the cursors.
    $idFromUser         = null;
    $idToPost           = null;
    $resultingDocument  = array();

    foreach ($cursorFrom as $key => $value) {
        $resultingDocument[$key] = $value;
        $idFromUser = $resultingDocument[$key]->getHandle();
    }

    foreach ($cursorTo as $key => $value) {
        $resultingDocument[$key] = $value;
        $idToPost = $resultingDocument[$key]->getHandle();
    }

    $edgeInfo   = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge('posted', $idFromUser, $idToPost, $linkBetween);

}

function postHasComment($idPost, $idComment){
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);

    $cursorFrom = $collectionHandler->byExample('post', ['_key' => $idPost]);
    $cursorTo   = $collectionHandler->byExample('post', ['_key' => $idPost]);

    // Now, we get the documents iterating over the cursors.
    $idFromPost         = null;
    $idToPost           = null;
    $resultingDocument  = array();

    foreach ($cursorFrom as $key => $value) {
        $resultingDocument[$key] = $value;
        $idFromPost = $resultingDocument[$key]->getHandle();
    }

    foreach ($cursorTo as $key => $value) {
        $resultingDocument[$key] = $value;
        $idToPost = $resultingDocument[$key]->getHandle();
    }

    $edgeInfo   = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge('has_comment', $idFromPost, $idToPost, $linkBetween);
}

function userCommented($idUser, $idComment){
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);

    $cursorFrom = $collectionHandler->byExample('post', ['username' => $idUser]);
    $cursorTo   = $collectionHandler->byExample('post', ['_key' => $idComment]);

    // Now, we get the documents iterating over the cursors.
    $idFromUser         = null;
    $idToPost           = null;
    $resultingDocument  = array();

    foreach ($cursorFrom as $key => $value) {
        $resultingDocument[$key] = $value;
        $idFromUser = $resultingDocument[$key]->getHandle();
    }

    foreach ($cursorTo as $key => $value) {
        $resultingDocument[$key] = $value;
        $idToPost = $resultingDocument[$key]->getHandle();
    }

    $edgeInfo   = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge('comments', $idFromUser, $idToPost, $linkBetween);
}