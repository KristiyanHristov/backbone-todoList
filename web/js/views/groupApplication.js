/**
 * Created by Kompa on 13-12-9.
 */

var GroupListApp = Backbone.View.extend({
    
    notebook: null,

    events: {
        'click #addGroup': 'addGroup'
    },

    initialize: function() {
        //Disable Bootstrap dropdown menu events
        this.disableDropdownClose();
        
        this.collection = new GroupCollection();
        this.collection.fetch();

        this.listenTo(this.collection, 'sync', this.render);
    },

    render: function() {
        
        if(this.notebook){
            var notebookCid = this.notebook.cid;
        }
        
        this.$el.find('ul').html('');
        var self = this;

        this.collection.each(function(item) {
            
            var groupView = new GroupView({
                model: item
            });
            
            // Focus newly created Notebook
            if(notebookCid === item.cid ) {
                groupView.showDetailedGroupView();
            }

            self.$el.find('ul').append(groupView.render().el);
        });
        
        return this;
        
    },

    addGroup: function() {
        var title = $('#groupTitle');
        this.notebook = this.collection.create({ title: title.val() });
        title.val("");
    },

    disableDropdownClose: function() {
        this.$el.find('.dropdown-menu input, .dropdown-menu label, .dropdown-menu textarea').click(function(e) {
            e.stopPropagation();
        });
    }
});
