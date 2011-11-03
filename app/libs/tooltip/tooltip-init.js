// loop throught all elements and finds all with class tooltipOn
// inside these elements finds first alement with class tooltipDesc 
// and use its content as a tooltip for the element above. 
// node with description will be removed
Event.observe(window,"load",function() {
       $$("*").findAll(function(node){
         return node.hasClassName('tooltipOn');
       }).each(function(node){
       	   for (var nodeDesc=node.firstDescendant(); nodeDesc; nodeDesc = nodeDesc.next()) {
       	   	   if (nodeDesc.hasClassName('tooltipDesc')) {
       	   	   	   new Tooltip(node, nodeDesc.innerHTML);
       	   	   	   nodeDesc.remove(); 
       	   	   	   break;
       	   	   }
       	   }
       });
     });

