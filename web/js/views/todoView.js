/**
 * Created by Kompa on 13-12-9.
 */

var TodoView = Backbone.View.extend({
    tagName: 'li class="list-group-item"',

    template: _.template($('#main-template').html()),

    events: {
        'click button.remove': 'deleteTodo',
        'click .editTodoDrop': 'editForm',
        'click .editTodo': 'editTodo'
    },

    initialize: function(options) {
        //attach options passed to do view because of change in BackBone v1.1.0+
        this.options = options || {};
    },

    render: function() {
        this.model.url = '/todo/' + this.options.notebookId + '/' + this.model.get("id");
        this.$el.html(this.template(this.model.toJSON()));
        return this;
    },

    deleteTodo: function() {
        this.model.destroy();
        this.remove();
    },

    editForm: function() {
        var title = this.$el.find('.todoEditTitle');
        var description = this.$el.find('.todoEditNotes');
        title.val(this.model.get('title'));
        description.val(this.model.get('description'));
    },

    editTodo: function() {
        var title = this.$el.find('.todoEditTitle');
        var description = this.$el.find('.todoEditNotes');

        this.model.set({
            title: title.val(),
            description: description.val()
        });
        
        this.model.save();
        this.render();
    },

    displayError: function() {
        alert(this.model.validationError);
    }
});
