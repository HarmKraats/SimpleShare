const mongoose = require('mongoose');
const File = require('../models/file');
const multer = require('multer');
const async = require('async')
const fs = require('fs')
const path = require('path')
const btoa = require('btoa')

const fileConfig = require('../config/file.config')

const mongoDB = fileConfig.dbConnection;
mongoose.connect(mongoDB, { useNewUrlParser: true });
mongoose.Promise = global.Promise;

module.exports = {
    getAll: (req, res, next) => {
        File.find().exec()
            .then(files => {
                console.log('File fetched successfully');
                res.send(files);
            })
            .catch(err => {
                return res.status(404).end();
            });
    },
    uploadFile: (req, res, next) => {
        let savedModels = []

        async.each(req.files, (file, callback) => {
            let fileModel = new File({
                name: file.filename
            });
            fileModel.save()
                .then((savedFileModel) => {
                    savedFileModel.encodedName = btoa(savedFileModel._id);
                    return savedFileModel.save();
                })
                .then((savedFileModel) => {
                    savedModels.push(savedFileModel);
                    console.log('File created successfully');
                    callback();
                })
                .catch((err) => {
                    return next('Error creating new file', err);
                });
        }, (err) => {
            if (err) {
                return res.status(400).end();
            }

            return res.send(savedModels)
        })
    },
    getFileOptions: () => {
        return {
            storage: multer.diskStorage({
                destination: fileConfig.uploadsFolder,
                filename: (req, file, cb) => {
                    let extension = fileConfig.supportedMimes[file.mimetype]
                    let originalname = file.originalname.split('.')[0]
                    let fileName = originalname + '-' + (new Date()).getMilliseconds() + '.' + extension
                    cb(null, fileName)
                }
            }),
            fileFilter: (req, file, cb) => {
                let extension = fileConfig.supportedMimes[file.mimetype]

                if (!extension) {
                    return cb(null, false)
                } else {
                    cb(null, true)
                }
            }
        }
    },
    downloadFile(req, res, next) {
        File.findOne({ name: req.params.name })
            .then((file) => {
                if (!file) {
                    return File.findOne({ encodedName: req.params.name });
                }
                return file;
            })
            .then((file) => {
                if (!file) {
                    return res.status(404).end();
                }
                let fileLocation = path.join(__dirname, '..', 'uploads', file.name);
                res.download(fileLocation, (err) => {
                    if (err) {
                        res.status(400).end();
                    }
                });
            })
            .catch((err) => {
                res.status(400).end();
            });
    },
    deleteFile(req, res, next) {
        File.findOne({ id: req.params._id })
          .then((file) => {
            if (!file) {
              return res.status(404).end();
            }
      
            let fileLocation = path.join(__dirname, '..', 'uploads', file.name);
            fs.unlink(fileLocation, () => {
              File.deleteOne(file)
                .then(() => {
                  return res.send([]);
                })
                .catch((err) => {
                  return next(err);
                });
            });
          })
          .catch((err) => {
            return res.status(400).end();
          });
      }
}
