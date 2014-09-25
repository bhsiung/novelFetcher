var VerticalReader = Class.create(
{
	pageText: null,
	wholeText: null,
	page: 0,

	initialize: function(element)
	{
		this.pageText = [];
		this.element = element;
		this.body = new Element('span',{
			class: 'bd'
		});
		this.wholeText = this.element.innerHTML;
		this.element.update(this.body);
		this.extractPageText();
		this.renderCurrentPage()
		this.renderNavigation();
		this.element.addClassName('ready')
	},
	gotoPage: function()
	{
		this.body.update(this.pageText[this.page]);
		this.element.select('.pagination a').each(function(link)
		{
			var href = link.readAttribute('href');
			var matches = href.match(/^#(\d+)$/);
			if(matches){
				var page = matches[1];
				if(page == this.page){
					link.addClassName('selected');
				}else{
					link.removeClassName('selected');
				}
			}
		}.bind(this))
	},
	renderNavigation: function()
	{
		var nextButton = new Element('a',{
			class: 'left arrow'
		});
		var prevButton = new Element('a',{
			class: 'right arrow'
		});
		var navigation = new Element('div',{class:'pagination'});
		for(var i=this.pageText.length-1; i>=0; i--){
			navigation.insert(new Element('a',{class:'','href':'#'+i}).observe('click',function(e){
				var link = e.element();
				var href = link.readAttribute('href');
				var matches = href.match(/^#(\d+)$/);
				if(matches){
					this.page = matches[1];
					this.gotoPage();
				}
			}.bind(this)))
		}
		this.element.insert(nextButton).insert(prevButton).insert(navigation);
		nextButton.observe('click',function(e){
			if(this.page<this.pageText.length-1){
				this.page++;
				this.gotoPage();
			}
		}.bind(this));
		prevButton.observe('click',function(e){
			if(this.page>0){
				this.page--;
				this.gotoPage();
			}
		}.bind(this));
	},
	renderCurrentPage: function()
	{
		this.body.update(this.pageText[0]);
	},
	extractPageText: function()
	{
		this.body.update(this.wholeText);
		var maxContentWidth = this.body.getWidth();
		this.body.update();

		var wholeText = this.wholeText;
		var usableWidth = this.element.getWidth() - parseInt(this.element.getStyle('paddingLeft')) - parseInt(this.element.getStyle('paddingRight'));
		var approxmatePage = maxContentWidth / usableWidth;
		var approxmateTextLengthPerPage = wholeText.length / approxmatePage;
		
		// tbd
		var offset = Math.ceil(approxmateTextLengthPerPage / 3);

		while(wholeText.length>0){
			this.body.insert(wholeText.substr(0,offset));
			if(this.body.getWidth()<usableWidth){ // ok
				//console.log('ok for offset',offset,wholeText.length,'left');
				if(wholeText.length <= offset){ // means it's all done
					//console.log('all done');
					this.pageText.push(this.body.innerHTML);
					this.body.update();
					break;
				}else{
					wholeText = wholeText.substr(offset,wholeText.length-offset);
				}
			}else{
				this.body.innerHTML = this.body.innerHTML.substr(0,this.body.innerHTML.length-offset);
				if(offset>1){ // not ok, reduce offset and redo
					offset = Math.ceil(offset/2);
					//console.log('offset become',offset)
				}else{
					//console.log('done');
					offset = Math.ceil(approxmateTextLengthPerPage / 3);
					this.pageText.push(this.body.innerHTML);
					this.body.update();
				}
			}
		}
		if(this.pageText.length<1){
			throw 'fail to extract';
		}
	}
});
$$('.vertical.reader:not(.ready)').each(function(div){
	new VerticalReader(div)
	var toggle = $('toggle');
	if(toggle){
		toggle.observe('click',function(e){
			div.togglClassName('vertical')
		})
	}
})


