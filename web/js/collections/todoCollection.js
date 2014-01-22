/**
 * Created by Kompa on 13-12-9.
 */

var TodoCollection = Backbone.Collection.extend({
    model: Todo,
    url: '/todo/'
});
