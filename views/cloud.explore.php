
<!DOCTYPE html>
<html>
<head>
    <title>HomeCloud</title>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <?= self::css('public/css/content'); ?>
    <?= self::js('public/js/jquery-2.1.0.min'); ?>
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="modal fade" id="modalRename">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Rename</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div>

    </div>
</div>

<header>

    <div class="width">

        <div id="path">
            <a href="<?= url() ?>" class="home">HomeCloud</a> /
            <?php foreach($bread as $crumb => $url): ?>
            <a href="<?= url($url) ?>"><?= $crumb ?></a> /
            <?php endforeach; ?>
        </div>

        <div id="actions">

            <input type="text" class="form-control" id="search" placeholder="Global search">

            <button type="button" class="btn btn-default">
                <span class="glyphicon glyphicon-plus-sign"></span> Folder
            </button>

            <button type="button" class="btn btn-primary">
                <span class="glyphicon glyphicon-circle-arrow-up"></span> Upload
            </button>

        </div>

    </div>

</header>

<div id="main" class="width">

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
                <td>
                    <?php if($item->isDir()): ?>
                    <span class="glyphicon glyphicon-folder-open folder"></span>
                    <a href="<?= url($path, $item->getFilename()) ?>"><?= $item->getFilename() ?></a>
                    <?php else: ?>
                    <span class="glyphicon glyphicon-file file"></span> <?= $item->getFilename() ?>
                    <?php endif; ?>
                </td>
                <td><?= number_format($item->getSize() / 1024, 2) ?> ko</td>
                <td><?= $item->isDir() ? 'folder' : mime_content_type($item->getPathname()) ?></td>
                <td><?= date('d M Y H:i', $item->getMTime()) ?></td>
                <td>
                    <div class="btn-group">
                        <a data-toggle="dropdown" href="#">
                            <span class="glyphicon glyphicon-cog"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="#" data-toggle="modal" data-target="#modalRename">
                                    <span class="glyphicon glyphicon-pencil"></span> Rename
                                </a>
                            </li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#modalMove">
                                    <span class="glyphicon glyphicon-share-alt"></span> Move
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#" data-toggle="modal" data-target="#modalDelete">
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

</div>

<footer class="width">HomeCloud - Personal Cloud Manager - &copy 2014 Aymeric Assier</footer>

</body>
</html>