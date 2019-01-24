<?php

namespace Chancenportal\Chancenportal\Controller;

/***
 *
 * This file is part of the "Chancenportal" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018
 *
 ***/

use Chancenportal\Chancenportal\Domain\Model\Log;
use Chancenportal\Chancenportal\Domain\Model\Offer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FrontendUserController
 */
class FrontendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \Chancenportal\Chancenportal\Domain\Repository\CategoryRepository
     * @inject
     */
    protected $categoryRepository = null;

    /**
     * @var \Chancenportal\Chancenportal\Domain\Repository\DistrictRepository
     * @inject
     */
    protected $districtRepository = null;

    /**
     * @var \Chancenportal\Chancenportal\Domain\Repository\TargetGroupRepository
     * @inject
     */
    protected $targetGroupRepository = null;

    /**
     * @var \Chancenportal\Chancenportal\Domain\Repository\OfferRepository
     * @inject
     */
    protected $offerRepository = null;

    /**
     * @var \Chancenportal\Chancenportal\Domain\Repository\LogRepository
     * @inject
     */
    protected $logRepository = null;

    /**
     * providerRepository
     *
     * @var \Chancenportal\Chancenportal\Domain\Repository\ProviderRepository
     * @inject
     */
    protected $providerRepository = null;

    /**
     * @var \Chancenportal\Chancenportal\Utility\SelectUtility
     * @inject
     */
    protected $selectUtility = null;
    /**
     * @var string
     */
    protected $entityNotFoundMessage = 'The requested entity could not be found.';

    /**
     * @var string
     */
    protected $unknownErrorMessage = 'An unknown error occurred.';

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
     * @return void
     * @throws \Exception
     * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
     */
    public function processRequest(\TYPO3\CMS\Extbase\Mvc\RequestInterface $request, \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response)
    {
        try {
            parent::processRequest($request, $response);
        } catch (\Exception $exception) {
            if ($exception instanceof \TYPO3\CMS\Extbase\Property\Exception\TargetNotFoundException || $exception instanceof \TYPO3\CMS\Extbase\Property\Exception\InvalidSourceException) {
                $GLOBALS['TSFE']->pageNotFoundAndExit($this->entityNotFoundMessage);
            }
            throw $exception;
        }
    }

    /**
     * @return void
     * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
     */
    protected function callActionMethod()
    {
        try {
            parent::callActionMethod();
        } catch (\Exception $exception) {
            // This enables you to trigger the call of TYPO3s page-not-found handler by throwing \TYPO3\CMS\Core\Error\Http\PageNotFoundException
            if ($exception instanceof \TYPO3\CMS\Core\Error\Http\PageNotFoundException) {
                $GLOBALS['TSFE']->pageNotFoundAndExit($this->entityNotFoundMessage);
            }

            // If the plugin is configured to do so, we call the page-unavailable handler.
            if (isset($this->settings['usePageUnavailableHandler']) && $this->settings['usePageUnavailableHandler']) {
                $GLOBALS['TSFE']->pageUnavailableAndExit($this->unknownErrorMessage, 'HTTP/1.1 500 Internal Server Error');
            }
            // Else we append the error message to the response. This causes the error message to be displayed inside the normal page layout. WARNING: the plugins output may gets cached.
            if ($this->response instanceof \TYPO3\CMS\Extbase\Mvc\Web\Response) {
                $this->response->setStatus(500);
            }
            $this->response->appendContent($exception->getMessage());
        }
    }

    /**
     * @param $hex
     * @param $percent
     * @return string
     */
    function colorLuminance($hex, $percent)
    {
        $hex = preg_replace('/[^0-9a-f]/i', '', $hex);
        $new_hex = '#';
        if (strlen($hex) < 6) {
            $hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
        }
        for ($i = 0; $i < 3; $i++) {
            $dec = hexdec(substr($hex, $i * 2, 2));
            $dec = min(max(0, $dec + $dec * $percent), 255);
            $new_hex .= str_pad(dechex($dec), 2, 0, STR_PAD_LEFT);
        }

        return $new_hex;
    }

    /**
     *
     */
    public function initThemeColors()
    {
        $query = $this->categoryRepository->createQuery();
        $query->matching($query->logicalAnd($query->equals('parent', '')));
        $categories = $query->execute();
        $categorieColors = [];
        foreach ($categories as $category) {
            if (!empty($category->getColor())) {
                $categorieColors[$category->getUid()] = [
                    'uid' => $category->getUid(),
                    'color' => $category->getColor(),
                    'color2' => $this->colorLuminance($category->getColor(), -0.4),
                    'color3' => $this->colorLuminance($category->getColor(), 0.2),
                ];
            }
        }
        $this->view->assign('themeColors', $categorieColors);
    }

    /**
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function teaserAction()
    {
        $this->view->assign('settings', $this->settings);
        $this->view->assign('perimeters', $this->selectUtility->getPerimeters());
        $this->view->assign('categories', $this->selectUtility->getCategoriesForSelect(null, true, true, true));
        $this->view->assign('districts', $this->selectUtility->getDistrictsForSelect(null, true));
        $this->view->assign('targetGroups', $this->selectUtility->getTargetGroupsForSelect(null, true));
        $this->initThemeColors();
    }

    /**
     * @ignorevalidation $offer
     * @param Offer $offer
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function offerDetailAction(\Chancenportal\Chancenportal\Domain\Model\Offer $offer)
    {
        $this->response->addAdditionalHeaderData('<title>' . htmlspecialchars($offer->getName() . ' - ' . $GLOBALS['TSFE']->rootLine[0]['title']) . '</title>');
        $this->response->addAdditionalHeaderData('<meta name="description" content="' . htmlspecialchars($offer->getShortDescription()) . '">');

        $log = new Log();
        $log->setOffer($offer);
        $this->logRepository->add($log);

        $this->view->assign('similarOffers', $this->offerRepository->findSimilarOffers($offer, 8));
        $this->view->assign('offer', $offer);
        $this->initThemeColors();
    }

    /**
     * @ignorevalidation $provider
     * @param \Chancenportal\Chancenportal\Domain\Model\Provider $provider
     */
    public function providerDetailAction(\Chancenportal\Chancenportal\Domain\Model\Provider $provider)
    {
        $this->response->addAdditionalHeaderData('<title>' . htmlspecialchars($provider->getName() . ' - ' . $GLOBALS['TSFE']->rootLine[0]['title']) . '</title>');
        $this->response->addAdditionalHeaderData('<meta name="description" content="' . htmlspecialchars($provider->getShortDescription()) . '">');
        $this->view->assign('provider', $provider);
        $this->initThemeColors();
    }

    /**
     * Ajax offer and provider search call
     */
    public function searchResultAjaxAction()
    {
        $this->view->assign('settings', $this->settings);
        $this->view->assign('offers', $this->offerRepository->findByFields(GeneralUtility::_POST()));
        $this->view->assign('providers', $this->providerRepository->findByFields(GeneralUtility::_POST()));
    }

    /**
     * Ajax provider search call
     */
    public function searchProviderResultAjaxAction()
    {
        $this->view->assign('settings', $this->settings);
        $this->view->assign('providers', $this->providerRepository->findByFields(GeneralUtility::_POST()));
    }

    /**
     * Aktuelle Angebote
     */
    public function offersTeaserAction()
    {
        $this->view->assign('settings', $this->settings);
        $this->view->assign('offers', $this->offerRepository->findAllActive(7));
        $this->initThemeColors();
    }

    /**
     * Auswahl einiger Anbieter
     */
    public function providerTeaserAction()
    {
        $providers = $this->providerRepository->findAllActive();
        $providers = $providers->toArray();
        shuffle($providers);
        $providers = array_slice($providers, 0, 4);

        $this->view->assign('settings', $this->settings);
        $this->view->assign('providers', $providers);
    }

    /**
     * Provider search result page
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function searchProviderResultsAction()
    {
        $sorting = [
            [
                "id" => "3",
                "title" => "A-Z",
                "active" => true
            ],
            [
                "id" => "1",
                "title" => "Neuste",
                "active" => false
            ]
        ];

        $this->view->assign('perimeters', $this->selectUtility->getPerimeters());
        $this->view->assign('settings', $this->settings);
        $this->view->assign('providers', $this->providerRepository->findByFields(GeneralUtility::_POST()));
        $this->view->assign('categories', $this->selectUtility->getProviderCategoriesForSelect());
        $this->view->assign('sorting', json_encode($sorting));
        $this->view->assign('districts', $this->selectUtility->getDistrictsForSelect(null, true));
        $this->view->assign('targetGroups', $this->selectUtility->getTargetGroupsForSelect(null, true));
    }

    /**
     * Offer search result page
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function searchResultsAction()
    {
        $tabConfig = [
            [
                "name" => 'Kachelansicht',
                "icon" => 'th-large',
                "active" => true,
                "actions" => [
                    [
                        "selector" => 'custom-map',
                        "prop" => [
                            "show" => false
                        ],
                    ],
                    [
                        "selector" => 'custom-pagination',
                        "prop" => [
                            "show" => true
                        ],
                    ]
                ]
            ],
            [
                "name" => 'Auf Karte anzeigen',
                "icon" => 'map-marker',
                "active" => false,
                "actions" => [
                    [
                        "selector" => 'custom-map',
                        "prop" => [
                            "show" => true
                        ],
                    ],
                    [
                        "selector" => 'custom-pagination',
                        "prop" => [
                            "show" => false
                        ],
                    ]
                ]
            ]
        ];

        $sortingOffers = [
            [
                "id" => "1",
                "title" => "Aktualität",
                "active" => true
            ],
            [
                "id" => 2,
                "title" => "Zuletzt eingestellt",
                "active" => false
            ]
        ];

        $sortingProviders = [
            [
                "id" => "3",
                "title" => "A-Z",
                "active" => true
            ],
            [
                "id" => "1",
                "title" => "Aktualität",
                "active" => false
            ]
        ];

        $this->view->assign('settings', $this->settings);
        $this->view->assign('offers', $this->offerRepository->findAllActive());
        $this->view->assign('providers', $this->providerRepository->findByFields(GeneralUtility::_POST()));
        $this->view->assign('perimeters', $this->selectUtility->getPerimeters());
        $this->view->assign('categories', $this->selectUtility->getCategoriesForSelect(null, true, true, true));
        $this->view->assign('districts', $this->selectUtility->getDistrictsForSelect(null, true));
        $this->view->assign('tabConfig', json_encode($tabConfig));
        $this->view->assign('sortingOffers', json_encode($sortingOffers));
        $this->view->assign('sortingProviders', json_encode($sortingProviders));
        $this->view->assign('targetGroups', $this->selectUtility->getTargetGroupsForSelect(null, true));
        $this->initThemeColors();
    }
}
