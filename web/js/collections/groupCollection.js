/**
 * Created by Kompa on 13-12-9.
 */

var GroupCollection = Backbone.Collection.extend({
    model: Group,
    url: '/notebook'
});
