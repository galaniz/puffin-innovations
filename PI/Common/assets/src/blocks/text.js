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
  SelectControl
} = window.wp.components

const {
  InspectorControls,
  PlainText
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
      padding_bottom = def.padding_bottom // eslint-disable-line camelcase
    } = attributes

    const TextTag = tag

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
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title={`Text${text ? `: ${text.split(' ').splice(0, 5).join(' ')}...` : ''}`} initialOpen={false}>
          <TextTag className={style}>
            <PlainText
              tag={tag}
              value={text} // eslint-disable-line camelcase
              onChange={v => setAttributes({ text: v })}
              placeholder='Text'
            />
          </TextTag>
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return null // Rendered in php
  }
})
