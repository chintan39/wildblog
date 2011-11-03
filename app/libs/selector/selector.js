//
//

var SelectorMenu = Class.create()

//------------------------------------------------------------------------------
// Constants
//------------------------------------------------------------------------------

SelectorMenu.VERSION = '0.1'


//------------------------------------------------------------------------------
// Static Methods
//------------------------------------------------------------------------------


SelectorMenu.setup = function(params)
{
    var selectorMenu, actualIndex, actualAttr, opts, i, a, actual, 
    spantext, optionsContainer, selectItemContainer, addItemContainer, 
    actualItemsContainer, mainElemId, button, newClear, closer, tmp
    
    selectorMenu = new SelectorMenu(params.parentElement)
	
    // set select element to be used as a source and destination
    if (params.selectField) {
      selectorMenu.setSelectField(params.selectField)
    }

    // set container field to be used to collect options
    if (params.containerField) {
      selectorMenu.setContainerField(params.containerField)
      selectorMenu.getContainerField().innerHTML = ''
    }

    // set display mode
    if (params.displayMode) {
      selectorMenu.setDisplayMode(params.displayMode)
    }

    // store options' attributes
    if (params.attributeFields) {
      selectorMenu.setAttributeFields(params.attributeFields)
    }

    if (params.updateOptions) {
    	// clear all childs
    	tmp = selectorMenu.getSelectField()
    	selectedFilds = {}
    	while (tmp.firstChild) {
    		selectedFilds[tmp.firstChild.value] = tmp.firstChild.selected
    		tmp.removeChild(tmp.firstChild);
    	}

    	for (i=0; i<params.attributeFields.length; i++) {
			tmp = new Element('option', {'value': params.attributeFields[i]['id']})
			tmp.update(params.attributeFields[i]['value'])
			tmp.selected = (selectedFilds[params.attributeFields[i]['id']] == true)
    		selectorMenu.getSelectField().appendChild(tmp)
    	}
    }
    
	// get source options from select element
	opts = selectorMenu.getSelectField().options

    actualIndex = selectorMenu.getSelectField().selectedIndex
   	selectorMenu.setMultiple(selectorMenu.getSelectField().multiple)
    mainElemId = selectorMenu.getSelectField().id
    
    // set source select element to not be displayed
    selectorMenu.getSelectField().style.display = 'none'
    
    // create container to hold actual items
    actualItemsContainer = new Element('div')
    actualItemsContainer.addClassName('actualItems')
    if (!selectorMenu.isMultiple()) {
  		actualItemsContainer.addClassName('floating')
  	}
   	selectorMenu.getContainerField().appendChild(actualItemsContainer);
   	selectorMenu.setActualItemsContaier(actualItemsContainer)
   	
    if (selectorMenu.isMultiple()) {
	  // create container to hold a button for adding a new item
   	  newClear = new Element('span')
	  newClear.addClassName('clear')
	  selectorMenu.getContainerField().appendChild(newClear)
	  
	  addItemContainer = new Element('div')
	  addItemContainer.addClassName('addNewItemButton')
      button = new Element('a', {href: '#'})
      button.onclick = function() {
      	  selectorMenu.showOptions()
      	  return false
      }
      addItemContainer.appendChild(button)
      selectorMenu.getContainerField().appendChild(addItemContainer);
   	} 
   	else {
      // create container to hold a button for selecting items
	  
      selectItemContainer = new Element('div')
      selectItemContainer.addClassName('selectItemButton')
      button = new Element('a', {href: '#'})
      button.onclick = function() {
      	  selectorMenu.showOptions()
      	  return false
      }
      selectItemContainer.appendChild(button)
   	  selectorMenu.getContainerField().appendChild(selectItemContainer);
   	  newClear = new Element('span')
	  newClear.addClassName('clear')
	  selectorMenu.getContainerField().appendChild(newClear)
   	}
    
    // clear separator
    newClear = new Element('span')
    newClear.addClassName('clear')
    selectorMenu.getContainerField().appendChild(newClear)
    
    // create container to hold options
    optionsContainer = new Element('div')
    optionsContainer.addClassName('options')
    optionsContainer.style.display = 'none'

    // button to close option list
    closer = new Element('a', {href: '#'})
    closer.addClassName('closer')
    closer.onclick = function () {
    	selectorMenu.hideOptions()
    	return false
    }
    optionsContainer.appendChild(closer)
    
    // button to add a new item
    if (params.addButtonFuncName) {
    	var adder = new Element('a', {href: '#'})
    	adder.addClassName('adder')
    	adder.onclick=params.addButtonFuncName
    	optionsContainer.appendChild(adder)
    }

    
   	// add elements to option container
    for (i = 0; i < opts.length; i++) {
    	actualAttr = selectorMenu.getAttribute(i)
    	if (actualAttr) {
    		a = selectorMenu.createOption(i, opts[i].innerHTML, actualAttr.image, actualAttr.indent)
    	} else {
    		a = selectorMenu.createOption(i, opts[i].innerHTML, '', 0)
    	}
    	a.onclick = function() {
    		selectorMenu.selectOption(this.value)
    		return false
    	}
    	optionsContainer.appendChild(a)
    }
    
    // clear separator after options
    newClear = new Element('span')
    newClear.addClassName('clear')
    optionsContainer.appendChild(newClear)
    
    selectorMenu.getContainerField().appendChild(optionsContainer)
    selectorMenu.setOptionsContainer(optionsContainer)
    
    // clear separator
    newClear = new Element('span')
    newClear.addClassName('clear')
    selectorMenu.getContainerField().appendChild(newClear)
    
   	// add selected elements to active container
    for (i = 0; i < opts.length; i++) {
    	if (opts[i].selected) {
    		selectorMenu.selectOption(i, true)
    	}
    }
}



//------------------------------------------------------------------------------
// Calendar Instance
//------------------------------------------------------------------------------

SelectorMenu.prototype = {

  // The HTML Container Element
  container: null,

  selectField: null,
  
  containerField: null,
  
  updateOptions: false,
  
  selectorMenu: this,
  
  multiple: false,
  
  actualItemsContaier: null,
  
  optionsContainer: null,
  
  displayMode: null,
  shouldDisplayImage: null,
  shouldDisplayText: null,
  
  options: [],
  
  attributeFields: [],
  
  //----------------------------------------------------------------------------

  initialize: function(parent)
  {
    this.create($(parent))
  },

  create: function(parent)
  {
  },

  setSelectField: function(field)
  {
    this.selectField = $(field)
  },

  setContainerField: function(field)
  {
    this.containerField = $(field)
  },
  
  setAttributeFields: function(fields)
  {
    this.attributeFields = fields
  },
  
  setMultiple: function(multiple)
  {
  	  this.multiple = multiple
  },
  
  setActualItemsContaier: function(actualItemsContaier)
  {
    this.actualItemsContaier = actualItemsContaier
  },
  
  setOptionsContainer: function(optionsContainer)
  {
  	this.optionsContainer = optionsContainer
  },
  
  setDisplayMode: function(displayMode)
  {
  	this.displayMode = displayMode
  	this.setShouldDisplayImage(displayMode == 'image' || displayMode == 'image_text' || displayMode == null)
  	this.setShouldDisplayText(displayMode == 'text' || displayMode == 'image_text' || displayMode == null)
  },
  
  isMultiple: function()
  {
  	  return this.multiple
  },
  
  getAttribute: function(index)
  {
  	return this.attributeFields[index]
  },

  getSelectField: function()
  {
    return this.selectField
  },

  getContainerField: function()
  {
    return this.containerField
  },
  
  getActualItemsContaier: function()
  {
    return this.actualItemsContaier
  },
  
  getOptionsContainer: function()
  {
  	return this.optionsContainer
  },
  
  getDisplayMode: function()
  {
  	return this.displayMode
  },
  
  setShouldDisplayImage: function(shouldDisplayImage)
  {
  	  this.shouldDisplayImage = shouldDisplayImage
  },
  
  setShouldDisplayText: function(shouldDisplayText)
  {
  	  this.shouldDisplayText = shouldDisplayText
  },
  
  getShouldDisplayImage: function()
  {
  	  return this.shouldDisplayImage
  },
  
  getShouldDisplayText: function()
  {
  	  return this.shouldDisplayText
  },
  
  // creates a new item (several elements) according specified parameters
  createOption: function(value, text, img, indent) {
  	var a
   	
  	a = new Element('a', {href: '#'})
  	if (!this.getShouldDisplayText()) {
  		a.addClassName('autowidth')
  	}
	a.appendChild(this.createItem(text, img, indent))
    a.value = value
	
    return a
  },
  
  createItem: function(text, img, indent) {
  	var img, span, container

  	container = new Element('span')
	
	span = new Element('span')
	span.addClassName('indent')
	span.style.width = (10*indent).toString() + 'px';
	container.appendChild(span)

	if (this.getShouldDisplayImage() && img != '') {
		img = new Element('img', {alt: text, src: img})
		img.title = text
		container.appendChild(img)
	}
	
	span = new Element('span')
	span.addClassName('indent')
	span.style.width = '4px';
	container.appendChild(span)
	
	if (this.getShouldDisplayText() && text != '') {
		span = new Element('span')
		span.addClassName('text')
		span.appendChild(document.createTextNode(text))
		container.appendChild(span)
	}

	span = new Element('span')
	span.addClassName('clear')
	container.appendChild(span)
	
	return container
  },

  dupplicateItem: function(item) {
  	var container, closer, content, actualAttr, opts, newclear
  	
  	container = new Element('span')
  	container.addClassName('activeItem')
  	if (!this.getShouldDisplayText()) {
  		container.addClassName('autowidth')
  	}
  	
  	actualAttr = this.getAttribute(item)
  	opts = this.getSelectField().options
  	if (actualAttr) {
  		content = this.createItem(opts[item].innerHTML, actualAttr.image, actualAttr.indent)
  	} else {
  		content = this.createItem(opts[item].innerHTML, '', 0)
  	}
  	content.value = item.value
  	
  	container.appendChild(content)
  	
  	if (this.isMultiple()) {
		closer = new Element('a', {href: '#'})
		closer.selector = this
		closer.addClassName('removeItem')
		closer.onclick = function() {
			this.selector.getSelectField().options[item].selected = false
			container.parentNode.removeChild(container)
			return false
		}
		container.appendChild(closer)

   	    newClear = new Element('span')
	    newClear.addClassName('clear')
	    container.appendChild(newClear)
  	}

  	return container
  },
  
  // this should be redesign
  showOptions: function() {
  	this.getOptionsContainer().style.display = 'block'
  },

  hideOptions: function() {
  	this.getOptionsContainer().style.display = 'none'
  },
  
  selectOption: function(optionIndex, forceAdd) {
  	var actualItemsContainer, shouldAdd, newItem

  	actualItemsContainer = this.getActualItemsContaier()
  	shouldAdd = true
  	
  	if (forceAdd == null)
  		forceAdd = false
  	
  	// update select element
  	if (this.isMultiple()) {
  		shouldAdd = !this.getSelectField().options[optionIndex].selected
  		this.getSelectField().options[optionIndex].selected = true
  	} else {
  		this.getSelectField().selectedIndex = optionIndex
		while (actualItemsContainer.hasChildNodes()) {
			actualItemsContainer.removeChild(actualItemsContainer.lastChild)
		}
  	}
  	
  	// add duplicated item to apropriate container
  	if (shouldAdd | forceAdd) {
  		newItem = this.dupplicateItem(optionIndex)
  		actualItemsContainer.appendChild(newItem)
  	}

  	this.hideOptions()
  }
  
}

