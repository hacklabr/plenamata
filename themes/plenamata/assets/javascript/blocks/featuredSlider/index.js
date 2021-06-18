import { MediaUpload, RichText } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";

const DraggableImage = ({ title, button, description, image, removeImage, setDescription, setButtons, setTitle }) => {
    return (
        <div className="slider-item-container">
            <img className='slider-item' src={image.url} key={image.id} />
            <div className="fields">
                <div className="title">
                <RichText
                    tagName="span"
                    className="title-field"
                    value={title}
                    onChange={setTitle}
                    placeholder={__('Type the title here', 'jaci')}
                />
                </div>
                
                <div className="description">
                <RichText
                    tagName="span"
                    className="description-field"
                    value={description}
                    onChange={setDescription}
                    placeholder={__('Type here your description', 'jaci')}
                />
                </div>

                <div className="featured-button">
                    <RichText
                        tagName="span"
                        className="button-field"
                        value={button}
                        onChange={setButtons}
                        placeholder={__('Type button text', 'jaci')}
                    />
                </div>
            </div>
           
            <div className="remove-item" onClick={removeImage}><span class="dashicons dashicons-trash"></span></div>
        </div>
    );
};

const ImageGallery = ({ images, imagesTitle, imagesDescriptions, imagesButtons, setAttributes }) => {
    const removeImage = (index) => {
        return () => {
            const newImages = images.filter((image, i) => {
                if (i != index) {
                    return image;
                }
            });

            imagesDescriptions.splice(index, 1);
            imagesTitle.splice(index, 1);
            imagesButtons.splice(index, 1);

            setAttributes({
                images: newImages,
                imagesDescriptions,
                imagesTitle,
                imagesButtons
            });
        }
    };

    const updateItem = (key, collection, index) => {
        return (content) => {
            setAttributes({
                [key]: collection.map((item, i) => {
                    if (i == index) {
                        return content;
                    } else {
                        return item;
                    }
                })
            });
        };
    };

    return (
        <div className="sliders-grid">
            {images.map((image, index) => {
                return (
                    <DraggableImage
                        collection={images}
                        title={imagesTitle[index]}
                        description={imagesDescriptions[index]}
                        button={imagesButtons[index]}
                        image={image}
                        index={index}
                        key={image.id}
                        removeImage={removeImage(index)}
                        setButtons={updateItem('imagesButtons', imagesButtons, index)}
                        setTitle={updateItem('imagesTitle', imagesTitle, index)}
                        setDescription={updateItem('imagesDescriptions', imagesDescriptions, index)}
                    />
                );
            })}
            <MediaUpload
                onSelect={(media) => { setAttributes({ images: [...images, ...media] }); }}
                type="image"
                multiple={true}
                value={images}
                render={({ open }) => (
                    <div className="select-images-button is-button is-default is-large" onClick={open}>
                        <span class="dashicons dashicons-plus"></span>
                    </div>
                )}
            />
        </div>
    );
};

wp.blocks.registerBlockType('jaci-theme/featured-slider', {
    title: __('Featured Slider', 'jaci'),
    icon: 'format-gallery',
    category: 'common',
    keywords: [
        __('materialtheme'),
        __('photos'),
        __('images')
    ],
    attributes: {
        images: {
            type: 'array',
        },

        imagesTitle: {
            type: 'array',
        },

        imagesDescriptions: {
            type: 'array',
        },

        imagesButtons: {
            type: 'array',
        },
    },

    edit({ attributes, className, setAttributes }) {
        const { images = [], imagesDescriptions = [], imagesTitle = [], imagesButtons = [] } = attributes;

        images.forEach((image, index) => {
            if ( ! imagesDescriptions[index] && imagesDescriptions[index] != '' ) {
                imagesDescriptions[index] = "";
            }

            if ( ! imagesTitle[index] && imagesTitle[index] != '') {
                imagesTitle[index] = "";
            }

            if ( ! imagesButtons[index] && imagesButtons[index] != '') {
                imagesButtons[index] = "";
            }
        });


        const onSortEnd = ({ newIndex, oldIndex }) => {
            setAttributes({
                images: arrayMove(images, oldIndex, newIndex),
                imagesTitle: arrayMove(imagesTitle, oldIndex, newIndex),
                imagesDescriptions: arrayMove(imagesDescriptions, oldIndex, newIndex),
                imagesButtons: arrayMove(imagesButtons, oldIndex, newIndex),
            });
        };

        if ( imagesTitle != attributes.imagesTitle ) {
            setAttributes( { ...attributes, imagesTitle } );
        }

        if ( imagesButtons != attributes.imagesButtons ) {
            setAttributes( { ...attributes, imagesButtons } );
        }

        if ( imagesDescriptions != attributes.imagesDescriptions ) {
            setAttributes( { ...attributes, imagesDescriptions } );
        }

        return (
            <div className="slider-image-gallery">
                <ImageGallery
                    axis="xy"
                    helperClass="moving"
                    helperContainer={document.querySelector('.sliders-grid')}
                    images={images}
                    imagesTitle={imagesTitle}
                    imagesDescriptions={imagesDescriptions}
                    imagesButtons={imagesButtons}
                    onSortEnd={onSortEnd}
                    pressDelay={200}
                    setAttributes={setAttributes}
                />
            </div>
        );
    },

    save: ({ attributes }) => {
        const { images = [], imagesDescriptions = [], imagesTitle = [], imagesButtons = [] } = attributes;

        const displayImages = (images) => {
            return (
                images.map((image, index) => {

                    return (
                        <div className="slide-item-wrapper">
                            <img
                                className='gallery-item'
                                key={images.id}
                                src={image.url}
                                alt={image.alt}
                            />
                            <div className="wrapper">
                                <div class="image-meta">
                                    <div class="image-title"> <RichText.Content tagName="span" value={imagesTitle[index]} /></div>
                                    <div class="image-description"> <RichText.Content tagName="span" value={imagesDescriptions[index]} /></div>
                                    <div class="image-button"> <RichText.Content tagName="span" value={imagesButtons[index]} /></div>
                                </div>
                            </div>

                        </div>
                    )
                })
            )
        }

        return (
            <div className="featured-slider alignfull">
                <div className="itens-wrapper">
                    {displayImages(images)}
                </div>
            </div>
        );

    },
});