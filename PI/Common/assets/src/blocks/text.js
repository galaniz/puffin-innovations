/**
 * Text block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  BaseControl,
  SelectControl,
  CheckboxControl
} = window.wp.components

const {
  InspectorControls,
  RichText,
  URLInputButton
} = window.wp.blockEditor

const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'text'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Block */

registerBlockType(name, {
  title: 'Text',
  category: 'theme-blocks',
  icon: 'editor-textcolor',
  attributes: attr,
  edit (props) {
    const { attributes, setAttributes } = props

    const {
      text = def.text,
      tag = def.tag,
      style = def.style,
      padding_top_mobile = def.padding_top_mobile, // eslint-disable-line camelcase
      padding_top = def.padding_top, // eslint-disable-line camelcase
      padding_bottom_mobile = def.padding_bottom_mobile, // eslint-disable-line camelcase
      padding_bottom = def.padding_bottom, // eslint-disable-line camelcase
      is_link = def.is_link, // eslint-disable-line camelcase
      link = def.link
    } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Text Options'>
            <SelectControl
              label='Tag'
              value={tag}
              options={[
                { label: 'Heading One', value: 'h1' },
                { label: 'Heading Two', value: 'h2' },
                { label: 'Heading Three', value: 'h3' },
                { label: 'Heading Four', value: 'h4' },
                { label: 'Heading Five', value: 'h5' },
                { label: 'Heading Six', value: 'h6' },
                { label: 'Paragraph', value: 'p' }
              ]}
              onChange={tag => setAttributes({ tag })}
            />
            <SelectControl
              label='Style'
              value={style}
              options={[
                { label: 'None', value: '' },
                { label: 'Heading One', value: 't-h1' },
                { label: 'Heading Two Large', value: 't-h2-l' },
                { label: 'Heading Two', value: 't-h2' },
                { label: 'Heading Three', value: 't-h3' },
                { label: 'Heading Four', value: 't-h4' },
                { label: 'Heading Five', value: 't-h5' },
                { label: 'Heading Six', value: 't-h6' },
                { label: 'Text Large', value: 't-l' },
                { label: 'Text Quote', value: 't-quote' },
                { label: 'Text Medium', value: 't-m' },
                { label: 'Text Regular', value: 't' },
                { label: 'Text Small', value: 't-s' },
                { label: 'Text Extra Small', value: 't-xs' }
              ]}
              onChange={style => setAttributes({ style })}
            />
            <SelectControl
              label='Padding Top'
              value={padding_top_mobile} // eslint-disable-line camelcase
              options={nO.padding_options}
              onChange={v => setAttributes({ padding_top_mobile: v })}
            />
            <SelectControl
              label='Padding Top Larger Screens'
              value={padding_top} // eslint-disable-line camelcase
              options={nO.padding_options}
              onChange={v => setAttributes({ padding_top: v })}
            />
            <SelectControl
              label='Padding Bottom'
              value={padding_bottom_mobile} // eslint-disable-line camelcase
              options={nO.padding_options}
              onChange={v => setAttributes({ padding_bottom_mobile: v })}
            />
            <SelectControl
              label='Padding Bottom Larger Screens'
              value={padding_bottom} // eslint-disable-line camelcase
              options={nO.padding_options}
              onChange={v => setAttributes({ padding_bottom: v })}
            />
            <CheckboxControl
              label='Link'
              value='1'
              checked={!!is_link} // eslint-disable-line camelcase
              onChange={v => setAttributes({ is_link: v })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title={`Text${text ? `: ${text.replace(/(<([^>]+)>)/gi, '').split(' ').splice(0, 5).join(' ')}...` : ''}`} initialOpen={false}>
          <RichText
            className={style}
            tagName={tag}
            tag={tag}
            allowedFormats={['core/bold', 'core/italic', 'core/link']}
            value={text} // eslint-disable-line camelcase
            onChange={v => setAttributes({ text: v })}
            placeholder='Text'
          />
          {is_link && ( // eslint-disable-line camelcase
            <BaseControl label='Link'>
              <URLInputButton
                url={link}
                onChange={link => setAttributes({ link })}
              />
            </BaseControl>
          )}
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return null // Rendered in php
  }
})
