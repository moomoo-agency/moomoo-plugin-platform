<?php

namespace MooMoo\Platform\Bundle\ConditionBundle\Command;

use MooMoo\Platform\Bundle\WpCliBundle\Model\WpCliCommandInterface;
use Symfony\Component\Yaml\Yaml;

class ConditionsListCommand implements WpCliCommandInterface
{
    const NAME = 'moomoo:conditions-list';
    
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     */
    public function execute($arguments = [])
    {
        $value = Yaml::parseFile(__DIR__.'/../Resources/config/setting_font.yml');

        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontType']['values'] =
            [];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['genericFamily']['values'] =
            [];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontFamily']['values'] =
            [];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontStyle']['values'] =
            [];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontWeight']['values'] =
            [];

        $value = $this->parseWebSafeFonts($value);
        $value = $this->parseGoogleFonts($value);

        $yaml = Yaml::dump($value, 10, 2);
        file_put_contents(__DIR__.'/../Resources/config/setting_font.yml', $yaml);
    }

    /**
     * @param array $value
     * @return array
     */
    private function parseWebSafeFonts(array $value)
    {
        $fonts = json_decode(file_get_contents(__DIR__.'/../Resources/assets/fonts/web_safe_fonts.json'), true);
        $options = $value['services']['builderius_setting.setting.font']['arguments'][0]['options'];
        $genericFamily = $options['genericFamily']['values'];
        $fontFamily = $options['fontFamily']['values'];
        $fontStyle = $options['fontStyle']['values'];
        $fontWeight = $options['fontWeight']['values'];

        foreach ($fonts as $item) {
            if (!in_array($item['type'], $genericFamily['common'])) {
                $genericFamily['common'][] = $item['type'];
            }
            if (!in_array($item['family'], $fontFamily['common.' . $item['type']]) &&
                $item['family'] !== strtolower($item['family'])) {
                $fontFamily['common.' . $item['type']][] = $item['family'];
                $fontStyle[$item['family']] = self::DEFAULT_FONT_STYLES;
                $fontWeight[$item['family']] = self::DEFAULT_FONT_WEIGHTS;
                /*foreach ($item['generic'] as $generic) {
                    if ($generic !== $item['type'] && !in_array($generic, self::DEFAULT_GENERIC_FAMILIES)) {
                        if (!in_array($generic, $fontFamily['common.' . $item['type']]) &&
                            $generic !== strtolower($generic)) {
                            $fontFamily['common.' . $item['type']][] = $generic;
                            $fontStyle[$generic] = self::DEFAULT_FONT_STYLES;
                            $fontWeight[$generic] = self::DEFAULT_FONT_WEIGHTS;
                        }
                    }
                }*/
            }
        };
        foreach ($fontFamily as $gf => $families) {
            sort($fontFamily[$gf]);
        }

        foreach (self::DEFAULT_GENERIC_FAMILIES as $defaultGenericFamily) {
            if (!in_array($defaultGenericFamily, $genericFamily['common'])) {
                $genericFamily['common'][] = $defaultGenericFamily;
            }
        }

        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontType']['values'][] =
            'common';
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['genericFamily'] = [
            'dependOn' => 'fontType',
            'values' => $genericFamily
        ];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontFamily'] = [
            'dependOn' => 'fontType.genericFamily',
            'values' => $fontFamily
        ];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontStyle'] = [
            'dependOn' => 'fontFamily',
            'values' => $fontStyle
        ];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontWeight'] = [
            'dependOn' => 'fontFamily',
            'values' => $fontWeight
        ];

        return $value;
    }

    /**
     * @param array $value
     * @return array
     */
    private function parseGoogleFonts(array $value)
    {
        $fonts = json_decode(file_get_contents(__DIR__.'/../Resources/assets/fonts/google_webfonts.json'), true);
        $options = $value['services']['builderius_setting.setting.font']['arguments'][0]['options'];
        $genericFamily = $options['genericFamily']['values'];
        $fontFamily = $options['fontFamily']['values'];
        $fontStyle = $options['fontStyle']['values'];
        $fontWeight = $options['fontWeight']['values'];
        foreach ($fonts['items'] as $item) {
            if (!in_array($item['category'], $genericFamily['google'])) {
                $genericFamily['google'][] = $item['category'];
            }
            $fontFamily['google.' . $item['category']][] = $item['family'];
            foreach ($item['variants'] as $variant) {
                if (!preg_match('/[0-9]/', $variant)) {
                    if ($variant === 'regular') {
                        $variant = 'normal';
                    }
                    $fontStyle[$item['family']][] = $variant;
                } elseif (!preg_match('/[a-z]/', $variant)) {
                    $fontWeight[$item['family']][] = (int)$variant;
                }
            }
            if (!isset($fontWeight[$item['family']])) {
                $fontWeight[$item['family']][] = 400;
            }
            if (!in_array(400, $fontWeight[$item['family']])) {
                $fontWeight[$item['family']][] = 400;
            }
            sort($fontWeight[$item['family']]);
        }

        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontType']['values'][] =
            'google';
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['genericFamily'] = [
            'dependOn' => 'fontType',
            'values' => $genericFamily
        ];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontFamily'] = [
            'dependOn' => 'fontType.genericFamily',
            'values' => $fontFamily
        ];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontStyle'] = [
            'dependOn' => 'fontFamily',
            'values' => $fontStyle
        ];
        $value['services']['builderius_setting.setting.font']['arguments'][0]['options']['fontWeight'] = [
            'dependOn' => 'fontFamily',
            'values' => $fontWeight
        ];

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalRegistrationParameters()
    {
        return [
            'shortdesc' => 'Generates font setting options'
        ];
    }
}
