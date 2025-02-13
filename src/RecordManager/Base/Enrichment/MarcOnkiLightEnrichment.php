<?php
/**
 * MarcOnkiLightEnrichment Class
 *
 * PHP version 7
 *
 * Copyright (C) The National Library of Finland 2014-2019.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @category DataManagement
 * @package  RecordManager
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/NatLibFi/RecordManager
 */
namespace RecordManager\Base\Enrichment;

/**
 * MarcOnkiLightEnrichment Class
 *
 * This is a class for enrichment of MARC records from an ONKI Light source.
 *
 * @category DataManagement
 * @package  RecordManager
 * @author   Ere Maijala <ere.maijala@helsinki.fi>
 * @author   Samuli Sillanpää <samuli.sillanpaa@helsinki.fi>
 * @license  http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * @link     https://github.com/NatLibFi/RecordManager
 */
class MarcOnkiLightEnrichment extends OnkiLightEnrichment
{
    /**
     * Enrich the record and return any additions in solrArray
     *
     * @param string $sourceId  Source ID
     * @param object $record    Metadata Record
     * @param array  $solrArray Metadata to be sent to Solr
     *
     * @return void
     */
    public function enrich($sourceId, $record, &$solrArray)
    {
        if (!($record instanceof \RecordManager\Base\Record\Marc)) {
            return;
        }
        $fields = [
            '650' => [
                'pref' => 'topic_add_txt_mv',
                'alt' => 'topic_alt_txt_mv',
                'check' => 'topic'
            ],
            '651' => [
                'pref' => 'geographic_add_txt_mv',
                'alt' => 'geographic_alt_txt_mv',
                'check' => 'geographic'
            ]
        ];
        foreach ($fields as $marcField => $spec) {
            foreach ($record->getFields($marcField) as $recField) {
                if ($id = $record->getSubfield($recField, '0')) {
                    $this->enrichField(
                        $sourceId,
                        $record,
                        $solrArray,
                        $id,
                        $spec['pref'],
                        $spec['alt'],
                        $spec['check']
                    );
                }
            }
        }
    }
}
