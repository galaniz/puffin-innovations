/**
 * Hero block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj,
  getColorSlug
} = window.blockUtils

const {
  Panel,
  PanelBody,
  PanelRow,
  ColorPalette,
  BaseControl,
  TextControl,
  CheckboxControl
} = window.wp.components

const {
  InspectorControls,
  PlainText,
  URLInputButton
} = window.wp.blockEditor

const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'hero'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Block */

registerBlockType(name, {
  title: 'Hero',
  category: 'theme-blocks',
  icon: 'align-full-width',
  attributes: attr,
  supports: {
    multiple: false
  },
  edit (props) {
    const { attributes, setAttributes } = props

    const {
      title_small = def.title_small, // eslint-disable-line camelcase
      title_large = def.title_large, // eslint-disable-line camelcase
      text = def.text,
      bg_color = def.bg_color, // eslint-disable-line camelcase
      video = def.video,
      video_link = def.video_link, // eslint-disable-line camelcase
      primary_link = def.primary_link, // eslint-disable-line camelcase
      primary_link_text = def.primary_link_text, // eslint-disable-line camelcase
      primary_link_color = def.primary_link_color, // eslint-disable-line camelcase
      secondary_link = def.secondary_link, // eslint-disable-line camelcase
      secondary_link_text = def.secondary_link_text, // eslint-disable-line camelcase
      secondary_link_color = def.secondary_link_color // eslint-disable-line camelcase
    } = attributes

    /* Style */

    const style = { margin: '1.25rem 0' }

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Hero Options'>
            <BaseControl label='Background Color'>
              <ColorPalette
                colors={nO.color_options}
                value={bg_color} // eslint-disable-line camelcase
                clearable={false}
                disableCustomColors
                disableAlpha
                onChange={bg_color => { // eslint-disable-line camelcase
                  const slug = getColorSlug(nO.color_options, bg_color)

                  setAttributes({
                    bg_color, // eslint-disable-line camelcase
                    bg_color_slug: slug,
                    bg_color_slug_meta: slug
                  })
                }}
              />
            </BaseControl>
            {primary_link && primary_link_text && ( // eslint-disable-line camelcase
              <BaseControl label='Primary Link Background Color'>
                <ColorPalette
                  colors={nO.color_options}
                  value={primary_link_color} // eslint-disable-line camelcase
                  clearable={false}
                  disableCustomColors
                  disableAlpha
                  onChange={primary_link_color => { // eslint-disable-line camelcase
                    const slug = getColorSlug(nO.color_options, primary_link_color)

                    setAttributes({
                      primary_link_color, // eslint-disable-line camelcase
                      primary_link_color_slug: slug
                    })
                  }}
                />
              </BaseControl>
            )}
            {secondary_link && secondary_link_text && ( // eslint-disable-line camelcase
              <BaseControl label='Secondary Link Color'>
                <ColorPalette
                  colors={nO.color_options}
                  value={secondary_link_color} // eslint-disable-line camelcase
                  clearable={false}
                  disableCustomColors
                  disableAlpha
                  onChange={secondary_link_color => { // eslint-disable-line camelcase
                    const slug = getColorSlug(nO.color_options, secondary_link_color)

                    setAttributes({
                      secondary_link_color, // eslint-disable-line camelcase
                      secondary_link_color_slug: slug
                    })
                  }}
                />
              </BaseControl>
            )}
            <CheckboxControl
              label='Video'
              value='1'
              checked={!!video}
              onChange={checked => setAttributes({ video: checked })}
            />
            {video && (
              <TextControl
                label='Video Embed Link'
                value={video_link} // eslint-disable-line camelcase
                onChange={v => setAttributes({ video_link: v })}
              />
            )}
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Hero' initialOpen={false}>
          <div style={style}>
            <BaseControl label='Title Small'>
              <PlainText
                value={title_small} // eslint-disable-line camelcase
                onChange={v => setAttributes({ title_small: v })}
                placeholder='Title Small'
                className='t-h5'
              />
            </BaseControl>
          </div>
          <div style={style}>
            <BaseControl label='Title Large'>
              <PlainText
                value={title_large} // eslint-disable-line camelcase
                onChange={v => setAttributes({ title_large: v })}
                placeholder='Title Large'
                className='t-h1'
              />
            </BaseControl>
          </div>
          <div style={style}>
            <BaseControl label='Text'>
              <PlainText
                value={text} // eslint-disable-line camelcase
                onChange={v => setAttributes({ text: v })}
                placeholder='Text'
                className='t-l'
              />
            </BaseControl>
          </div>
          <div style={style}>
            <BaseControl label='Primary Link'>
              <PanelRow>
                <PlainText
                  value={primary_link_text} // eslint-disable-line camelcase
                  onChange={v => setAttributes({ primary_link_text: v })}
                  placeholder='Primary Link Text'
                  className='t-h5'
                />
                <URLInputButton
                  url={primary_link} // eslint-disable-line camelcase
                  onChange={v => setAttributes({ primary_link: v })}
                />
              </PanelRow>
            </BaseControl>
          </div>
          <div style={style}>
            <BaseControl label='Secondary Link'>
              <PanelRow>
                <PlainText
                  value={secondary_link_text} // eslint-disable-line camelcase
                  onChange={v => setAttributes({ secondary_link_text: v })}
                  placeholder='Secondary Link Text'
                  className='t-h5'
                />
                <URLInputButton
                  url={secondary_link} // eslint-disable-line camelcase
                  onChange={v => setAttributes({ secondary_link: v })}
                />
              </PanelRow>
            </BaseControl>
          </div>
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return null // Rendered in php
  }
})
