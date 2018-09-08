<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7cms.com>
 * @copyright      (c) 2012-2018, Pierre-Henry Soria. All Rights Reserved.
 * @license        GNU General Public License; See PH7.LICENSE.txt and PH7.COPYRIGHT.txt in the root directory.
 * @package        PH7 / App / System / Module / Admin / Controller
 */

namespace PH7;

use PH7\Framework\Cache\Cache;
use PH7\Framework\Layout\Html\Design;
use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Navigation\Page;
use PH7\Framework\Url\Header;

class ModeratorController extends Controller
{
    const ITEMS_PER_PAGE = 20;

    /** @var ModeratorModel */
    private $oModeratorModel;

    /** @var Page */
    private $oPage;

    /** @var string */
    private $sMsg;

    /** @var string */
    private $sMsgType;

    public function __construct()
    {
        parent::__construct();

        $this->oPage = new Page();
        $this->oModeratorModel = new ModeratorModel;
    }

    public function index()
    {
        $this->view->page_title = $this->view->h2_title = t('Moderation Panel');

        $this->output();
    }

    public function pictureAlbum()
    {
        $this->view->page_title = $this->view->h2_title = t('Photo Albums Moderation');

        $this->view->total_pages = $this->oPage->getTotalPages(
            $this->oModeratorModel->totalPictureAlbums(),
            self::ITEMS_PER_PAGE
        );

        $this->view->current_page = $this->oPage->getCurrentPage();

        $this->view->albums = $this->oModeratorModel->getAlbumsPicture(
            $this->oPage->getFirstItem(),
            $this->oPage->getNbItemsPerPage()
        );

        $this->output();
    }

    public function picture()
    {
        $this->view->page_title = $this->view->h2_title = t('Pictures Moderation');

        $this->view->total_pages = $this->oPage->getTotalPages(
            $this->oModeratorModel->totalPictures(),
            self::ITEMS_PER_PAGE
        );

        $this->view->current_page = $this->oPage->getCurrentPage();

        $this->view->pictures = $this->oModeratorModel->getPictures(
            $this->oPage->getFirstItem(),
            $this->oPage->getNbItemsPerPage()
        );

        $this->output();
    }

    public function videoAlbum()
    {
        $this->view->page_title = $this->view->h2_title = t('Video Albums Moderation');

        $this->view->total_pages = $this->oPage->getTotalPages(
            $this->oModeratorModel->totalVideoAlbums(),
            self::ITEMS_PER_PAGE
        );

        $this->view->current_page = $this->oPage->getCurrentPage();

        $this->view->albums = $this->oModeratorModel->getAlbumsVideo(
            $this->oPage->getFirstItem(),
            $this->oPage->getNbItemsPerPage()
        );

        $this->output();
    }

    public function video()
    {
        $this->design->addCss(
            PH7_LAYOUT . PH7_SYS . PH7_MOD . 'video/' . PH7_TPL . PH7_TPL_MOD_NAME . PH7_SH . PH7_CSS,
            'common.css'
        );

        $this->view->page_title = $this->view->h2_title = t('Videos Moderation');

        $this->view->total_pages = $this->oPage->getTotalPages(
            $this->oModeratorModel->totalVideos(),
            self::ITEMS_PER_PAGE
        );

        $this->view->current_page = $this->oPage->getCurrentPage();

        $this->view->videos = $this->oModeratorModel->getVideos(
            $this->oPage->getFirstItem(),
            $this->oPage->getNbItemsPerPage()
        );

        $this->output();
    }

    public function avatar()
    {
        $this->view->page_title = $this->view->h2_title = t('Profile Photos Moderation');

        $this->view->total_pages = $this->oPage->getTotalPages(
            $this->oModeratorModel->totalAvatars(),
            self::ITEMS_PER_PAGE
        );
        $this->view->current_page = $this->oPage->getCurrentPage();

        $this->view->avatars = $this->oModeratorModel->getAvatars(
            $this->oPage->getFirstItem(),
            $this->oPage->getNbItemsPerPage()
        );

        $this->view->avatarDesign = new AvatarDesignCore; // Avatar Design Class

        $this->output();
    }

    public function background()
    {
        $this->view->page_title = $this->view->h2_title = t('Profile Backgrounds Moderation');

        $this->view->total_pages = $this->oPage->getTotalPages(
            $this->oModeratorModel->totalBackgrounds(),
            self::ITEMS_PER_PAGE
        );
        $this->view->current_page = $this->oPage->getCurrentPage();

        $this->view->backgrounds = $this->oModeratorModel->getBackgrounds(
            $this->oPage->getFirstItem(),
            $this->oPage->getNbItemsPerPage()
        );

        $this->output();
    }

    public function pictureWebcam()
    {
        Header::redirect(
            Uri::get(
                'webcam',
                'webcam',
                'picture'
            ),
            t('Welcome to the Picture Webcam in "administrator mode"')
        );
    }

    public function approvedPictureAlbum()
    {
        if ($this->oModeratorModel->approvedPictureAlbum($this->httpRequest->post('album_id'))) {
            PictureCore::clearCache();

            $this->sMsg = t('The photo album has been approved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The photo album could not be approved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'picturealbum'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function approvedPhoto()
    {
        if ($this->oModeratorModel->approvedPicture($this->httpRequest->post('picture_id'))) {
            PictureCore::clearCache();

            $this->sMsg = t('The picture has been approved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The picture could not be approved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'picture'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function approvedVideoAlbum()
    {
        if ($this->oModeratorModel->approvedVideoAlbum($this->httpRequest->post('album_id'))) {
            VideoCore::clearCache();

            $this->sMsg = t('The video album has been approved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The video album could not be approved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'videoalbum'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function approvedVideo()
    {
        if ($this->oModeratorModel->approvedVideo($this->httpRequest->post('video_id'))) {
            VideoCore::clearCache();

            $this->sMsg = t('The video has been approved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The video could not be approved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'video'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function approvedAvatar()
    {
        if ($this->oModeratorModel->approvedAvatar($this->httpRequest->post('id'))) {
            $this->clearAvatarCache();

            $this->sMsg = t('The profile photo has been approved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The profile photo could not be approved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'avatar'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function approvedBackground()
    {
        if ($this->oModeratorModel->approvedBackground($this->httpRequest->post('id'))) {
            $this->clearUserBgCache();

            $this->sMsg = t('The wallpaper has been approved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The wallpaper could not be approved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'background'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function disapprovedPictureAlbum()
    {
        if ($this->oModeratorModel->approvedPictureAlbum($this->httpRequest->post('album_id'), '0')) {
            PictureCore::clearCache();

            $this->sMsg = t('The photo album has been disapproved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The photo album could not be disapproved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'picturealbum'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function disapprovedPhoto()
    {
        if ($this->oModeratorModel->approvedPicture($this->httpRequest->post('picture_id'), '0')) {
            PictureCore::clearCache();

            $this->sMsg = t('The picture has been disapproved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The picture could not be disapproved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'picture'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function disapprovedVideoAlbum()
    {
        if ($this->oModeratorModel->approvedVideoAlbum($this->httpRequest->post('album_id'), '0')) {
            VideoCore::clearCache();

            $this->sMsg = t('The video album has been disapproved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The video album could not be disapproved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'videoalbum'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function disapprovedVideo()
    {
        if ($this->oModeratorModel->approvedVideo($this->httpRequest->post('video_id'), '0')) {
            VideoCore::clearCache();

            $this->sMsg = t('The video has been disapproved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The video could not be disapproved!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'video'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function disapprovedAvatar()
    {
        if ($this->oModeratorModel->approvedAvatar($this->httpRequest->post('id'), 0)) {
            $this->clearAvatarCache();

            $this->sMsg = t('The profile photo has been disapproved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The profile photo could not be disapprove!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'avatar'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function disapprovedBackground()
    {
        if ($this->oModeratorModel->approvedBackground($this->httpRequest->post('id'), 0)) {
            $this->clearUserBgCache();

            $this->sMsg = t('The wallpaper has been disapproved!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The wallpaper could not be disapprove!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'background'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function deletePictureAlbum()
    {
        if (
            (new PictureCoreModel)->deletePhoto($this->httpRequest->post('id'), $this->httpRequest->post('album_id')) &&
            $this->oModeratorModel->deletePictureAlbum($this->httpRequest->post('album_id'))
        ) {
            $sDir = PH7_PATH_PUBLIC_DATA_SYS_MOD . 'picture/img/' . $this->httpRequest->post('username') . PH7_DS . $this->httpRequest->post('album_id') . PH7_DS;
            $this->file->deleteDir($sDir);
            PictureCore::clearCache();

            $this->sMsg = t('The photo album has been deleted!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The photo album could not be deleted');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'picturealbum'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function deletePhoto()
    {
        $bPicture = (new PictureCoreModel)->deletePhoto(
            $this->httpRequest->post('id'),
            $this->httpRequest->post('album_id'),
            $this->httpRequest->post('picture_id')
        );

        if ($bPicture) {
            (new PictureCore)->deletePhoto(
                $this->httpRequest->post('album_id'),
                $this->httpRequest->post('username'),
                $this->httpRequest->post('picture_link')
            );
            PictureCore::clearCache();

            $this->sMsg = t('The picture has been deleted!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The picture could not be deleted!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'picture'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function deleteVideoAlbum()
    {
        if (
            (new VideoCoreModel)->deleteVideo($this->httpRequest->post('id'), $this->httpRequest->post('album_id')) &&
            $this->oModeratorModel->deleteVideoAlbum($this->httpRequest->post('album_id'))
        ) {
            $sDir = PH7_PATH_PUBLIC_DATA_SYS_MOD . 'video/file/' . $this->httpRequest->post('username') . PH7_DS . $this->httpRequest->post('album_id') . PH7_DS;
            $this->file->deleteDir($sDir);
            VideoCore::clearCache();

            $this->sMsg = t('The video album has been deleted!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The video album could not be deleted');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'videoalbum'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function deleteVideo()
    {
        $bVideo = (new VideoCoreModel)->deleteVideo(
            $this->httpRequest->post('id'),
            $this->httpRequest->post('album_id'),
            $this->httpRequest->post('video_id')
        );

        if ($bVideo) {
            (new VideoCore)->deleteVideo(
                $this->httpRequest->post('album_id'),
                $this->httpRequest->post('username'),
                $this->httpRequest->post('video_link')
            );
            VideoCore::clearCache();

            $this->sMsg = t('The video has been deleted!');
            $this->sMsgType = Design::SUCCESS_TYPE;
        } else {
            $this->sMsg = t('Oops! The video could not be deleted!');
            $this->sMsgType = Design::ERROR_TYPE;
        }

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'video'
            ),
            $this->sMsg,
            $this->sMsgType
        );
    }

    public function deleteAvatar()
    {
        (new Admin)->deleteAvatar($this->httpRequest->post('id'), $this->httpRequest->post('username'));
        $this->clearAvatarCache();

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'avatar'
            ),
            $this->sMsg
        );
    }

    public function deleteBackground()
    {
        (new Admin)->deleteBackground($this->httpRequest->post('id'), $this->httpRequest->post('username'));
        $this->clearUserBgCache();

        Header::redirect(
            Uri::get(
                PH7_ADMIN_MOD,
                'moderator',
                'background'
            ),
            $this->sMsg
        );
    }

    /**
     * Clear "Design Avatar" & "UserCoreModel Avatar" Cache
     *
     * @return void
     */
    private function clearAvatarCache()
    {
        (new Cache)
            ->start(Design::CACHE_AVATAR_GROUP . $this->httpRequest->post('username'), null, null)->clear()
            ->start(UserCoreModel::CACHE_GROUP, 'avatar' . $this->httpRequest->post('id'), null)->clear();
    }

    /**
     * Clear UserCoreModel Background Cache
     *
     * @return void
     */
    private function clearUserBgCache()
    {
        (new Cache)->start(
            UserCoreModel::CACHE_GROUP,
            'background' . $this->httpRequest->post('id'),
            null
        )->clear();
    }
}
