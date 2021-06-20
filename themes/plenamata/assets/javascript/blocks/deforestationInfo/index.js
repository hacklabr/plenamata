import { registerBlockType } from "@wordpress/blocks";
import { __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { TextControl } from '@wordpress/components';
import { __ } from "@wordpress/i18n";

registerBlockType('jaci/deforestation-info', {
    title: __('Deforestation Info', 'jaci'),
    icon: 'info-outline',
    category: 'common',

    edit({ attributes, setAttributes }) {
        const {
            boxTitle = __('Desmatados na Amazônia nos últimos 07 dias.', 'jaci'),
            count = 0,
            dataSource = ''
        } = attributes;

        return (
            <>
                <TextControl
                    value={ boxTitle }
                    onChange={ ( boxTitle ) => setAttributes( { boxTitle } ) }
                    placeholder={__('Type the box title', 'jaci')}
                />
                <NumberControl
                    isShiftStepEnabled={ true }
                    onChange={ ( count ) => setAttributes( { count } ) }
                    shiftStep={ 1 }
                    value={ count }
                    placeholder={__('Type the count', 'jaci')}
                />
                <TextControl
                    value={ dataSource }
                    onChange={ ( dataSource ) => setAttributes( { dataSource } ) }
                    placeholder={__('Type the source of data', 'jaci')}
                />
            </>
        );
    },

    save: ({ attributes }) => {
        return (
            <div>
                <span className="box-title">{attributes.boxTitle}</span>
                <div className="box-content">
                    <span className="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="88" height="54" viewBox="0 0 88 54" fill="none"><path d="M87.3046 36.4079C87.7403 38.5004 87.9336 40.7187 87.2402 42.7376C86.6909 44.3392 85.614 45.7168 84.356 46.8521C79.9808 50.7978 73.7676 51.7827 67.9257 52.5313C55.435 54.1299 42.3767 55.3602 30.5702 50.985C27.5634 49.8681 24.7007 48.4077 21.8687 46.9104C14.505 43.0107 7.11063 38.6599 1.95909 32.1124C1.01102 30.9096 0.118165 29.5565 0.0107771 28.0286C-0.105815 26.3472 0.744081 24.767 1.56943 23.2943C2.8949 20.9318 3.92582 18.7994 5.22674 16.4552C5.34641 16.2405 5.49368 16.0349 5.66243 15.8508C12.3573 8.62209 20.7642 3.41533 30.3616 1.15099C31.687 0.838031 33.0309 0.589505 34.384 0.402344C29.9934 1.87816 25.0597 3.55033 22.2891 7.26594C20.9329 9.0854 20.0984 11.2393 19.3988 13.4024C18.8281 15.1758 18.3342 16.983 18.0642 18.827C16.8614 26.9608 20.1966 35.4598 26.106 41.1759C32.0184 46.892 40.2627 49.9111 48.4825 50.1657C53.6892 50.3253 58.9727 49.414 63.6579 47.1343C68.343 44.8547 72.3992 41.1636 74.7525 36.5152C77.5722 30.9434 77.7778 24.1135 75.2925 18.3852C74.547 16.6608 73.47 14.9303 71.8899 13.5957C74.5316 15.8079 77.0261 18.1827 79.2536 20.809C83.0797 25.3163 86.0926 30.6212 87.3046 36.4079Z" fill="#FFF087"></path><path d="M75.2926 18.3853C77.7778 24.1136 77.5722 30.9435 74.7525 36.5153C72.3992 41.1637 68.343 44.8548 63.6579 47.1344C58.9727 49.4141 53.6892 50.3254 48.4825 50.1658C40.2627 49.9112 32.0184 46.892 26.106 41.176C20.1966 35.4599 16.8614 26.9609 18.0642 18.8271C18.3342 16.9831 18.8282 15.1759 19.3988 13.4025C20.0984 11.2394 20.933 9.08549 22.2891 7.26603C25.0597 3.55042 29.9934 1.87824 34.384 0.402432H34.3871C42.9413 -0.772695 51.8667 0.611072 59.4329 4.74703C61.7402 6.005 63.9064 7.50229 66.0296 9.04867C67.9841 10.4723 69.914 11.9451 71.7733 13.4976C71.8132 13.5283 71.85 13.562 71.8899 13.5958C73.47 14.9304 74.547 16.6609 75.2926 18.3853ZM52.33 26.8136C54.0114 25.0494 53.7414 21.9751 52.106 20.1679C50.4707 18.3576 46.5495 18.3085 45.5308 18.7197C44.5122 19.1308 41.6772 21.2939 41.5667 22.5703C41.4164 24.2946 42.7879 25.7766 44.2361 26.7216C46.6906 28.3232 50.305 28.9338 52.33 26.8136Z" fill="#FF7373"></path><path d="M52.1058 20.1679C53.7412 21.9751 54.0112 25.0494 52.3298 26.8137C50.3048 28.9338 46.6904 28.3232 44.2358 26.7216C42.7876 25.7766 41.4162 24.2947 41.5665 22.5703C41.677 21.2939 44.512 19.1309 45.5306 18.7197C46.5493 18.3086 50.4705 18.3577 52.1058 20.1679Z" fill="#FFF087"></path></svg>
                    </span>
                    <span className="count">{attributes.count}</span>
                    <span className="legend">{__('hectare', 'jaci')}</span>
                </div>
                <span className="data-source">{attributes.dataSource}</span>
            </div>
        );

    },
});