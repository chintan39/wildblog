/*
Copyright (C) 2012  Honza Horak <horak.honza@gmail.com>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

var LinkSelector = Class.create()

//------------------------------------------------------------------------------
// Constants
//------------------------------------------------------------------------------

LinkSelector.VERSION = '0.1'

LinkSelector.setup = function(params)
{

  function param_default(name, def) {
    if (!params[name]) params[name] = def
  }

  param_default('container', null)
  param_default('dataField', null)
  param_default('triggerElement', null)
  
  LinkSelector.linkSelector = new LinkSelector(params.dataField)
  
  LinkSelector.linkSelector.setContainer(params['container'])
  LinkSelector.linkSelector.setDataField(params['dataField'])
  LinkSelector.linkSelector.setTriggerElement(params['triggerElement'])
  
  new Ajax.Request('linkslist/', {
  	  method:'get',
	  onSuccess: function(response) {
		// Handle the response content...
		LinkSelector.linkSelector.setLinksData(response.responseText.evalJSON())
		LinkSelector.linkSelector.init()
	  }
	});

}


LinkSelector.prototype = {

  // The HTML Container Element
  container: null,

  // Callbacks
  triggerElement: null,

  // data field
  dataField: null,
  selectField: null,


  //----------------------------------------------------------------------------
  // Initialize
  //----------------------------------------------------------------------------

  initialize: function(parent)
  {
    this.create()
  },

  create: function(parent)
  {
  },
  
  init: function() 
  {
	  var sel = new Element('select', { name: 'linkselector', id: this.dataField + '_select' });
	  sel.onchange = function () {
	  	  $(LinkSelector.linkSelector.getDataField()).value = $(LinkSelector.linkSelector.getSelectField()).value;
	  }
	  
	  var opt = new Element('option', { value: ''}).update('-- Choose link --');
	  sel.appendChild(opt)
	  
	  var shouldSwitch = false;
	  
	  for (p in this.linksData) {
		  var opt = new Element('option', { value: '', disabled: true}).update(p);
		  sel.appendChild(opt)
		  for (c in this.linksData[p]) {
			  var opt = new Element('option', { value: '', disabled: true}).update('&nbsp;&nbsp;' + c);
			  sel.appendChild(opt)
			  for (i=0; i<this.linksData[p][c].size(); i++) {
				  opt = new Element('option', { value: this.linksData[p][c][i]['link'] }).update('&nbsp;&nbsp;&nbsp;&nbsp;' + this.linksData[p][c][i]['title']);
				  if (this.linksData[p][c][i]['link'] == $(this.dataField).value) {
					  shouldSwitch = true
					  opt.selected = true
				  }
				  sel.appendChild(opt)
			  }
		  }
	  }
	  
	  // add a select
  	  $(this.container).appendChild(sel)
  	  this.setSelectField(sel)
  	  
  	  /*
  	  if (shouldSwitch) {
  	  	  $(this.dataField).style.display = 'none'
  	  	  sel.style.display = 'block'
  	  } else {
  	  	  $(this.dataField).style.display = 'block'
  	  	  sel.style.display = 'none'
  	  }
  	  
	  $(this.triggerElement).onclick = function() {
	  	  $(LinkSelector.linkSelector.getDataField()).toggle();
	  	  $(LinkSelector.linkSelector.getSelectField()).toggle();
	  	  if ($(LinkSelector.linkSelector.getDataField()).visible()) {
	  	  	  $(LinkSelector.linkSelector.getDataField()).focus();
	  	  } else {
	  	  	  $(LinkSelector.linkSelector.getSelectField()).focus();
	  	  }
		  return false
	  }
	  */
  },
  
  setLinksData: function(data)
  {
  	  this.linksData = data
  },
  
  setContainer: function(container) 
  {
  	  this.container = container
  },
  
  setDataField: function(dataField) 
  {
  	  this.dataField = dataField
  },
  
  getDataField: function() 
  {
  	  return this.dataField
  },
  
  setSelectField: function(selectField) 
  {
  	  this.selectField = selectField
  },
  
  getSelectField: function() 
  {
  	  return this.selectField
  },
  
  setTriggerElement: function(triggerElement) 
  {
  	  this.triggerElement = triggerElement
  }
  
}

// global object that remembers the link selector
window._popupLinkSelector = null

