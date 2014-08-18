<?php self::layout('views/_layout') ?>
<?= self::css('public/css/content', 'public/css/dropzone'); ?>
<?= self::js('public/js/dropzone', 'public/js/main'); ?>

<div class="modal fade" id="modalCreate">
    <div class="modal-dialog">

        <form action="<?= url(':' . $path, 'create') ?>" method="post">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create folder</h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" name="name" placeholder="Folder name">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>

        </form>

    </div>
</div>

<div class="modal fade" id="modalRename">
    <div class="modal-dialog">

        <form action="<?= url(':' . $path, 'rename') ?>" method="post">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rename</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="name" value="">
                    <input type="text" class="form-control" name="to" placeholder="New name">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>

        </form>

    </div>
</div>

<div class="modal fade" id="modalDelete">
    <div class="modal-dialog">

        <form action="<?= url(':' . $path, 'delete') ?>" method="post">

            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="name" value="">
                    <p>All inner items will be deleted as well.</p>
                    <p>Are you really sure to delete <strong class="str_name"></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary">Yes</button>
                </div>
            </div>

        </form>

    </div>
</div>

<div class="modal fade" id="modalUpload">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-body">

                <form action="<?= url(':' . $path, 'upload') ?>" method="post" enctype="multipart/form-data" class="dropzone" id="uploadZone">
                    <div class="fallback">
                        <input type="file" name="file" />
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<header>

    <div class="width">

        <div id="path">
            <a href="<?= url() ?>" class="home">HomeCloud</a> /
            <?php foreach($bread as $crumb => $url): ?>
            <a href="<?= url(':' . $url) ?>"><?= $crumb ?></a> /
            <?php endforeach; ?>
        </div>

        <div id="actions">

            <form action="<?= url(':' . $path) ?>" method="post">
                <input type="text" class="form-control" id="search" name="search" value="<?= $query ?>" placeholder="Find file or folder">
            </form>

            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modalCreate">
                <span class="glyphicon glyphicon-plus-sign"></span> Folder
            </button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalUpload">
                <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload
            </button>

        </div>

    </div>

</header>

<div id="main" class="width">

    <?php if($message = flash('success')): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if($message = flash('error')): ?>
    <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>

    <?php if(!$items): ?>
    <div class="nothing"><?= $query ? 'No results' : 'Empty folder' ?></div>
        <?php if(!$query): ?>
        <form action="<?= url(':' . $path, 'upload') ?>" method="post" enctype="multipart/form-data" class="dropzone" id="uploadZone">
            <div class="fallback">
                <input type="file" name="file" />
            </div>
        </form>
        <?php endif; ?>
    <?php else: ?>
    <table class="table table-hover" id="dir-list">
        <thead>
        <tr>
            <th>Name</th>
            <th>Size</th>
            <th>Type</th>
            <th>Date</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): /** @var $item \SplFileinfo */ ?>
            <tr>
                <td class="col-name">
                    <?php if($item->isDir()): ?>
                    <span class="glyphicon glyphicon-folder-open folder"></span>
                    <a href="<?= url(':' . str_replace(HC_ROOT, null, $item->getPathname())) ?>"><?= $item->getFilename() ?></a>
                    <?php else: ?>
                    <span class="glyphicon glyphicon-file file"></span>
                    <a href="<?= self::asset(HC_DIR . str_replace(HC_ROOT, null, $item->getPathname())) ?>" target="_blank"><?= $item->getFilename() ?></a>
                    <?php endif; ?>
                </td>
                <td class="col-size"><?= number_format($item->getSize() / 1024, 2) ?> ko</td>
                <td class="col-type"><?= $item->isDir() ? 'folder' : mime_content_type($item->getPathname()) ?></td>
                <td class="col-date"><?= date('d M Y H:i', $item->getMTime()) ?></td>
                <td class="col-actions">
                    <div class="btn-group">
                        <a data-toggle="dropdown" href="#">
                            <span class="glyphicon glyphicon-cog"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" class="rename" data-toggle="modal" data-target="#modalRename" data-name="<?= $item->getFilename() ?>">
                                    <span class="glyphicon glyphicon-pencil"></span> Rename
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#" class="delete" data-toggle="modal" data-target="#modalDelete" data-name="<?= $item->getFilename() ?>">
                                    <span class="glyphicon glyphicon-remove"></span> Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</div>

<footer class="width">HomeCloud - Personal Cloud Manager - &copy 2014 Aymeric Assier</footer>