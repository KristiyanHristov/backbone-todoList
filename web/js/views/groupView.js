/**
 * Created by Kompa on 13-12-9.
 */

var GroupView = Backbone.View.extend({

    tagName: 'li class="list-group-item"',

    template: _.template($('#group-template').html()),
    
    listApp: null,

    events: {
        'click button.remove': 'deleteGroup',
        'click .editGroupDrop': 'editForm',
        'click .editGroup': 'editGroup',
        'click .group-link': 'showDetailedGroupView'
    },

    initialize: function() {
        this.listenTo(this.model, 'destroy', this.remove);
        this.listenTo(this.model, 'change', this.render);
    },

    render: function() {
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    },

    deleteGroup: function() {
        
        // Destroy Notebook  model
        this.model.destroy();
        
        // Refresh TodoListApp View
        if(this.listApp) {
            this.listApp.remove();
        }
        
    },

    editForm: function() {
        var title = this.$el.find('.groupEditTitle');
        title.val(this.model.get('title'));
    },

    editGroup: function() {
        var title = this.$el.find('.groupEditTitle');

        this.model.set({
            title: title.val()
        });

        if(!this.model.save({validate:true})) {
            this.displayError();
        }
    },

    showDetailedGroupView: function() {

        this.$el.parent().find('li').css({'background':'#fff'});
        this.$el.css({'background':'#eee'});
        
        // Clean container
        $('.todoList').remove();
        
        // Remove old View
        if(this.listApp) {
            this.listApp.remove();
        }

        var listApp = new TodoListApp({
            model: this.model
        });

        // Move the view element into the DOM (replacing the old content)
        $(".notebook").append(listApp.el);
        
        // Render view after it is in the DOM (styles are applied)
        $(listApp.render()).appendTo(".notebook");
        
        this.listApp = listApp;
    },

    displayError: function() {
        alert(this.model.validationError);
    }
});
