/**
 * Created by Kompa on 13-12-9.
 */

var TodoListView = Backbone.View.extend({

    initialize: function(options) {
        //attach options passed to do view because of change in BackBone v1.1.0+
        this.options = options || {};
        
        this.listenTo(this.collection, 'add', this.render);
        this.listenTo(this.collection, 'sync', this.render);
    },

    render: function() {
        
        this.$el.html('');
        
        this.collection.each(function(item) {
            var todoView = new TodoView({
                model: item,
                notebookId: this.options.notebookId
            });

            this.$el.append(todoView.render().el);
        }, this);

        //Disable Bootstrap dropdown menu events
        this.disableDropdownClose(); 
        
        return this;
    },

    disableDropdownClose: function() {
        $('.notebook').find('.dropdown-menu input, .dropdown-menu label, .dropdown-menu textarea').click(function(e) {
            e.stopPropagation();
        });
    }
});
