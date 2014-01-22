/**
 * Created by Kompa on 13-12-9.
 */

var TodoListApp = Backbone.View.extend({

    tagName: 'div class="col-md-8 todoList"',

    template: _.template($('#todoList-template').html()),
    
    events: {
        'click .addTodo': 'addTodo'
    },
    
    remove: function() {
        $('.todoList').empty().detach();
        return this;
    },
    
    // TodoCollection
    todos: null,

    render: function() {

        this.todos = new TodoCollection();
        
        $('.todoList').append(this.template({title: this.model.get('title')}));

        // Show AddTodo Button (+)
        $('#addTodoDropdown').show();

        var collection = this.todos;
        var notebookId = this.model.get('id');

        this.todos.fetch({
            url: "/todo/" + notebookId,
            success: function() {
                
                var todoList = new TodoListView({
                    el: '#todo-list',
                    collection: collection,
                    notebookId: notebookId
                });
                todoList.render();

            }
        });

        //Disable Bootstrap dropdown menu events
        this.disableDropdownClose(); 
       
    },

    addTodo: function() {
        
        var title = $('#todoTitle');
        var description = $('#todoNotes');
        
        this.todos.create({ 
            title: title.val(), 
            description: description.val() 
        },{ 
            url:'/todo/' + this.model.get('id')
        });

        title.val('');
        description.val('');
    },

    disableDropdownClose: function() {
        $('.todoList').find('.dropdown-menu input, .dropdown-menu label, .dropdown-menu textarea').click(
            function(e) {
                e.stopPropagation();
        });
    }
});
