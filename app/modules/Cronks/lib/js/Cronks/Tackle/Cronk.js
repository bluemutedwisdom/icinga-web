/*global Ext: false, Icinga: false, _: false */
Ext.ns('Icinga.Cronks.Tackle');

(function () {
    "use strict";

    Icinga.Cronks.Tackle.Cronk = Ext.extend(Ext.Panel, {

        constructor: function (config) {

            config = Ext.apply(config || {}, {
                layout: 'border',
                border: false,
                defaults: {
                    border: false
                }
            });

            Icinga.Cronks.Tackle.Cronk.superclass.constructor.call(this, config);
        },

        initComponent: function () {
            Icinga.Cronks.Tackle.Cronk.superclass.initComponent.call(this);
            
            var type = 'host';
            this.tabItems = {
                host: {},
                service: {}
            }
            this.objectGrid = new Icinga.Cronks.Tackle.ObjectGrid({
                region: 'center'
            });
            

            this.infoTabs = new Icinga.Cronks.Tackle.InfoTabPanel();
            
            for(var i in this.tabItems) {
                this.tabItems[i].head = new Icinga.Cronks.Tackle.Information.Head({
                    type: i
                    
                });
                this.tabItems[i].comments = new Icinga.Cronks.Tackle.Comment.Panel({
                    type: i
                });
                this.tabItems[i].commands = new Icinga.Cronks.Tackle.Command.Panel({
                    type: i
                });
                
                this.tabItems[i].relation = new Icinga.Cronks.Tackle.Relation.Head({
                	type : i
                });
                
                // add all items and hide service items
                for(var x in this.tabItems[i]) {
                    this.infoTabs.add(this.tabItems[i][x]);
                   
                }
            }
            
            this.collapsibleFrame = new Ext.Panel({
                layout: 'fit',
                iconCls: 'icinga-icon-universal',
                region: 'south',
                height: 300,
                minSize: 300,
                maxSize: 600,
                collapsible: true,
                split: true,
                border: false,
                items: this.infoTabs
            });

            this.add([this.collapsibleFrame, this.objectGrid]);
            this.initInternalEvents();
            this.infoTabs.on("afterrender",function() {
                this.toggleTabView('host');
            },this,{single: true})
        },

        toggleTabView: function(type) {
            var show = type;
            var hide = type === "host" ? "service" : "host"

            for(var i in this.tabItems[show]) {
                this.infoTabs.unhideTabStripItem(this.tabItems[show][i]);
            }
            for(var i in this.tabItems[hide]) {
                this.infoTabs.hideTabStripItem(this.tabItems[hide][i]);
            }
            this.infoTabs.setActiveTab(this.tabItems[show].head);
        },

        initInternalEvents: function () {
            /**
             * TODO: these objects should just need the record and be able to deal with it
             */
            this.objectGrid.on("hostSelected", function(record) {
                this.toggleTabView('host');
                
                this.tabItems.host.head.loadDataForObjectId(record.data.HOST_OBJECT_ID);
                this.tabItems.host.relation.loadDataForObjectId(record.data.HOST_OBJECT_ID);
                this.tabItems.host.comments.grid.recordUpdated(record);

                this.tabItems.host.comments.form.setObjectData({
                    objectName : record.data.HOST_NAME,
                    objectId : record.data.HOST_ID,
                    record: record,
                    objectInstance : record.data.INSTANCE_NAME
                });
                
                this.tabItems.host.commands.form.setRecord(record);
                
                if (this.collapsibleFrame.collapsed === true) {
                    this.collapsibleFrame.expand(true);
                }
            },this);

            this.objectGrid.on("serviceSelected", function(record) {
                if(!record.data)
                    return;
                
                this.toggleTabView('service');

                this.tabItems.service.head.loadDataForObjectId(record.data.SERVICE_OBJECT_ID);
                this.tabItems.service.relation.loadDataForObjectId(record.data.SERVICE_OBJECT_ID);
                this.tabItems.service.comments.grid.recordUpdated(record);

                this.tabItems.service.comments.form.setObjectData({
                    objectName : record.data.SERVICE_NAME,
                    objectId : record.data.SERVICE_ID,
                    record: record,
                    objectInstance : record.data.INSTANCE_NAME
                });
                
                this.tabItems.service.commands.form.setRecord(record);
                
                if (this.collapsibleFrame.collapsed === true) {
                    this.collapsibleFrame.expand(true);
                }
            },this);

        }
    });

    Ext.reg('cronks-tackle-cronk', Icinga.Cronks.Tackle.Cronk);

})();
