adw_entity_query

Steps:
    - Install module
    - Go to module directory and run composer install


Module is in Workshop\Module namespace, to start using put:

use Workshop\Module\EntityQuery;

at the top of the file where you're using it.


Then:
Instantiate an instance of EntityQuery for each node / entity you want to reference.

Assuming $node is in scope:
$prevQuery = new EntityQuery();
$nextQuery = new EntityQuery();
$prev = $prevQuery->filterEntityNode($node, 'prev')->execute(); // instance of "previous" node (stdClass)
$next = $nextQuery->filterEntityNode($node, 'next')->execute(); // instance of "next" node (stdClass)

Access node attributes directly or use methods:
$prevQuery->getTitle($prev) // grabs title of previous node
