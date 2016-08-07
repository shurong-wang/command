
var mongodb = require("mongodb");
var MongoClient = mongodb.MongoClient;
var ObjectId = mongodb.ObjectID;
var express = require("express");
var app = express();
app.listen(3000);

var COL = "conn";
var URL = "mongodb://localhost/" + COL;

app.get("/", function(req, res){
    MongoClient.connect(URL, function(err, db){
        if(err){
            console.log(err);
            res.send("数据库连接失败");
            return;
        }
        var cursor = db.collection(COL).find().sort({"_id":-1});
        var data = [];
        cursor.each(function(err, doc){
            if(null == doc){
                db.close();
                console.dir(data);
                res.json(data);
                return;
            }
            data.push(doc);
        });
    });
});

app.get("/:handle", function(req, res){
    var handle = req.params.handle;
    var query = req.query || {};
    var condition = {};
    var handles = ["find", "insertOne", "updateMany", "deleteMany", "count"];
    if(-1 == handles.indexOf(handle)){
        console.log("操作命令错误");
        res.send("操作命令错误，请使用 find insertOne updateMay deleteMany count");
        return;
    }
    if(("updateMany" == handle || "deleteMany" == handle) && query.hasOwnProperty("id")){
        condition = {"id" : "" + query.id}
        delete query.id;
        if(query.hasOwnProperty("newid")){
            query.id = query.newid;
            delete query.newid;
        }
    }
    console.log(condition);
    console.log(query);

    MongoClient.connect(URL, function (err, db) {
        if(err){
            console.log(err);
            res.send("数据库连接失败");
            return;
        }
        if("find" == handle){
            var cursor = db.collection(COL).find(query).limit(1);
            var data = [];
            cursor.each(function(err, doc){
                if(null == doc){
                    db.close();
                    console.dir(data);
                    res.json(data);
                    return;
                }
                data.push(doc);
            });

        }else if("insertOne" == handle){
            db.collection(COL).insert(query, function (err, ret) {
                if(err){
                    console.log(err);
                    res.send("插入数据失败");
                    return;
                }
                db.close();
                console.dir(ret.result);
                res.json(ret.result);
                return;
            });
        }else if("updateMany" == handle){
            db.collection(COL).updateMany(condition, {$set: query}, function(err, ret){
                if(err){
                    console.log(err);
                    res.send("修改数据失败");
                    return;
                }
                db.close();
                console.dir(ret.result);
                res.json(ret.result);
                return;
            });
        }else if("deleteMany" == handle){
            db.collection(COL).deleteMany(query, function(err, ret){
                if(err){
                    console.log(err);
                    res.send("删除数据失败");
                    return;
                }
                db.close();
                console.dir(ret.result);
                res.json(ret.result);
                return;
            });
        }else if("count" == handle){
            db.collection(COL).count(query).then(function(count){
                db.close();
                console.dir(count);
                res.send("" + count);
                return;
            });
        }

    });
});